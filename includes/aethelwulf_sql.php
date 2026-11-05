<?php
/**
 * ezSQL v1.26 — upgraded for PHP 8.x using mysqli
 *
 * Migration notes:
 * - The mysql_* extension (removed in PHP 7) is replaced with mysqli throughout.
 * - case-insensitive constants (3rd arg to define()) are gone in PHP 8 — removed.
 * - All undeclared dynamic properties are now declared (required pattern in 8.2+).
 * - The old PHP4-style constructor db() is now __construct() (methods named
 *   after the class are no longer constructors).
 * - stripslashes() removed from escape(): it was a magic_quotes-era workaround;
 *   magic quotes no longer exist, so stripping slashes would corrupt legitimate
 *   backslashes in user input.
 */

define("EZSQL_DB_USER", "$SQLUSERNAME");     // <-- mysql db user
define("EZSQL_DB_PASSWORD", "$SQLPASSWORD"); // <-- mysql db password
define("EZSQL_DB_NAME", "$SQLDATABASE");     // <-- mysql db name
define("EZSQL_DB_HOST", "$SQLSERVER");       // <-- mysql server host

// ==================================================================
//  ezSQL Constants
define("EZSQL_VERSION", "1.26a");
define("OBJECT", "OBJECT");
define("ARRAY_A", "ARRAY_A");
define("ARRAY_N", "ARRAY_N");

// ==================================================================
//  The Main Class

class db {

    public bool $trace = false;      // same as $debug_all
    public bool $debug_all = false;  // same as $trace
    public bool $show_errors = true;
    public int $num_queries = 0;
    public ?string $last_query = null;
    public ?array $col_info = null;
    public bool $debug_called = false;
    public bool $vardump_called = false;

    public ?mysqli $dbh = null;
    /** @var array<int, object>|null */
    public ?array $last_result = null;
    public ?mysqli_result $result = null;
    public int $num_rows = 0;
    public int $insert_id = 0;
    public int $rows_affected = 0;
    public string $func_call = "";

    // ==================================================================
    //  DB Constructor - connects to the server and selects a database

    public function __construct(string $dbuser, string $dbpassword, string $dbname, string $dbhost)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->dbh = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);
            $this->dbh->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $e) {
            $this->dbh = null;
            $this->print_error(
                "<p>IMPORTANT: Our database is currently down. We are working to bring the database server up as soon as possible. Thank you for your patience.</p>"
            );
        }

        // Restore manual error handling so query()/select() can report
        // errors through print_error() the way ezSQL expects.
        mysqli_report(MYSQLI_REPORT_OFF);
    }

    // ==================================================================
    //  Select a DB (if another one needs to be selected)

    public function select(string $db): bool
    {
        if ($this->dbh === null || !$this->dbh->select_db($db)) {
            $this->print_error(
                "<ol><b>Error selecting database <u>" . htmlspecialchars($db) . "</u>!</b>" .
                "<li>Are you sure it exists?" .
                "<li>Are you sure there is a valid database connection?" .
                "</ol>"
            );
            return false;
        }
        return true;
    }

    // ====================================================================
    //  Format a string correctly for safe insert
    //
    //  NOTE: the old stripslashes() call was a magic_quotes workaround and
    //  has been removed intentionally — it corrupted data containing real
    //  backslashes once magic quotes were gone.

    public function escape(string $str): string
    {
        if ($this->dbh === null) {
            return addslashes($str); // no connection: best-effort fallback
        }
        return $this->dbh->real_escape_string($str);
    }

    // ==================================================================
    //  Print SQL/DB error.

    public function print_error(string $str = ""): bool
    {
        // All errors go to the global error array $EZSQL_ERROR.
        global $EZSQL_ERROR;

        $error_no = 0;

        // If no special error string then use the mysqli default.
        if ($str === "") {
            $str      = $this->dbh ? $this->dbh->error : "No database connection";
            $error_no = $this->dbh ? $this->dbh->errno : 0;
        }

        // Log this error to the global array.
        $EZSQL_ERROR[] = [
            "query"     => $this->last_query,
            "error_str" => $str,
            "error_no"  => $error_no,
        ];

        // Is error output turned on or not?
        if ($this->show_errors) {
            print "<blockquote><font face=arial size=2 color=ff0000>";
            print "<b>SQL/DB Error --</b> ";
            print "[<font color=000077>" . htmlspecialchars($str) . "</font>]";
            print "</font></blockquote>";
            // mail("webminister@outlandsheralds.org", "CCA sql query error", "$str ($error_no)", "From: webminister@outlandsheralds.org");
            return true;
        }

        return false;
    }

    // ==================================================================
    //  Turn error handling on or off.

    public function show_errors(): void
    {
        $this->show_errors = true;
    }

    public function hide_errors(): void
    {
        $this->show_errors = false;
    }

    // ==================================================================
    //  Kill cached query results

    public function flush(): void
    {
        $this->last_result = null;
        $this->col_info    = null;
        $this->last_query  = null;
    }

    // ==================================================================
    //  Basic Query - see docs for more detail

    public function query(string $query)
    {
        // For reg expressions
        $query = trim($query);

        // Initialise return
        $return_val = 0;

        // Flush cached values
        $this->flush();

        // Log how the function was called
        $this->func_call = "\$db->query(\"$query\")";

        // Keep track of the last query for debug
        $this->last_query = $query;

        if ($this->dbh === null) {
            $this->print_error();
            return false;
        }

        // Perform the query
        $this->result = $this->dbh->query($query);
        $this->num_queries++;

        // If there is an error then take note of it
        if ($this->dbh->error) {
            $this->print_error();
            return false;
        }

        // Query was an insert, delete, update, replace
        if (preg_match("/^(insert|delete|update|replace)\s+/i", $query)) {
            $this->rows_affected = $this->dbh->affected_rows;

            // Take note of the insert_id
            if (preg_match("/^(insert|replace)\s+/i", $query)) {
                $this->insert_id = $this->dbh->insert_id;
            }

            // Return number of rows affected
            $return_val = $this->rows_affected;
        }
        // Query was a select
        else {
            if ($this->result instanceof mysqli_result) {
                // Take note of column metadata
                $this->col_info = $this->result->fetch_fields();

                // Store query results
                $rows = [];
                while ($row = $this->result->fetch_object()) {
                    $rows[] = $row;
                }

                $this->result->free_result();

                $this->last_result = $rows;
                $this->num_rows    = count($rows);
            } else {
                $this->last_result = null;
                $this->num_rows    = 0;
            }

            // Return number of rows selected
            $return_val = $this->num_rows;
        }

        // If debug ALL queries
        if ($this->trace || $this->debug_all) {
            $this->debug();
        }

        return $return_val;
    }

    // ==================================================================
    //  Get one variable from the DB - see docs for more detail

    public function get_var(?string $query = null, int $x = 0, int $y = 0)
    {
        // Log how the function was called
        $this->func_call = "\$db->get_var(\"$query\",$x,$y)";

        // If there is a query then perform it, if not use cached results
        if ($query !== null) {
            $this->query($query);
        }

        // Extract var out of cached results based on x,y vals
        $values = null;
        if (isset($this->last_result[$y])) {
            $values = array_values(get_object_vars($this->last_result[$y]));
        }

        // If there is a value return it, else return null
        return (isset($values[$x]) && $values[$x] !== '') ? $values[$x] : null;
    }

    // ==================================================================
    //  Get one row from the DB - see docs for more detail

    public function get_row(?string $query = null, string $output = OBJECT, int $y = 0)
    {
        // Log how the function was called
        $this->func_call = "\$db->get_row(\"$query\",$output,$y)";

        // If there is a query then perform it, if not use cached results
        if ($query !== null) {
            $this->query($query);
        }

        // If the output is an object then return object using the row offset
        if ($output == OBJECT) {
            return $this->last_result[$y] ?? null;
        }
        // If the output is an associative array then return row as such
        elseif ($output == ARRAY_A) {
            return isset($this->last_result[$y]) ? get_object_vars($this->last_result[$y]) : null;
        }
        // If the output is a numerical array then return row as such
        elseif ($output == ARRAY_N) {
            return isset($this->last_result[$y]) ? array_values(get_object_vars($this->last_result[$y])) : null;
        }
        // If invalid output type was specified
        else {
            $this->print_error(" \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N");
            return null;
        }
    }

    // ==================================================================
    //  Function to get 1 column from the cached result set based on X index

    public function get_col(?string $query = null, int $x = 0): array
    {
        // If there is a query then perform it, if not use cached results
        if ($query !== null) {
            $this->query($query);
        }

        $new_array = [];

        if (is_array($this->last_result)) {
            for ($i = 0, $n = count($this->last_result); $i < $n; $i++) {
                $new_array[$i] = $this->get_var(null, $x, $i);
            }
        }

        return $new_array;
    }

    // ==================================================================
    //  Return the query as a result set - see docs for more details

    public function get_results(?string $query = null, string $output = OBJECT): ?array
    {
        // Log how the function was called
        $this->func_call = "\$db->get_results(\"$query\", $output)";

        // If there is a query then perform it, if not use cached results
        if ($query !== null) {
            $this->query($query);
        }

        // Send back array of objects. Each row is an object
        if ($output == OBJECT) {
            return $this->last_result;
        }
        elseif ($output == ARRAY_A || $output == ARRAY_N) {
            if (is_array($this->last_result)) {
                $new_array = [];
                foreach ($this->last_result as $row) {
                    $converted = get_object_vars($row);

                    if ($output == ARRAY_N) {
                        $converted = array_values($converted);
                    }

                    $new_array[] = $converted;
                }
                return $new_array;
            }
            return null;
        }

        return null;
    }

    // ==================================================================
    //  Function to get column meta data info pertaining to the last query

    public function get_col_info(string $info_type = "name", int $col_offset = -1)
    {
        if (is_array($this->col_info)) {
            if ($col_offset == -1) {
                $new_array = [];
                foreach ($this->col_info as $col) {
                    $new_array[] = $col->{$info_type} ?? null;
                }
                return $new_array;
            }

            return $this->col_info[$col_offset]->{$info_type} ?? null;
        }

        return null;
    }

    // ==================================================================
    //  Dumps the contents of any input variable to screen in a nicely
    //  formatted and easy to understand way - any type: Object, Var or Array

    public function vardump($mixed = ''): void
    {
        echo "<p><table><tr><td bgcolor=ffffff><blockquote><font color=000090>";
        echo "<pre><font face=arial>";

        if (!$this->vardump_called) {
            echo "<font color=800080><b>ezSQL</b> (v" . EZSQL_VERSION . ") <b>Variable Dump..</b></font>\n\n";
        }

        $var_type = gettype($mixed);
        print_r($mixed ? $mixed : "<font color=red>No Value / False</font>");
        echo "\n\n<b>Type:</b> " . ucfirst($var_type) . "\n";
        echo "<b>Last Query</b> [" . $this->num_queries . "]<b>:</b> " . ($this->last_query ?: "NULL") . "\n";
        echo "<b>Last Function Call:</b> " . ($this->func_call ?: "None") . "\n";
        echo "<b>Last Rows Returned:</b> " . (is_array($this->last_result) ? count($this->last_result) : 0) . "\n";
        echo "</font></pre></font></blockquote></td></tr></table>" . $this->donation();
        echo "\n<hr size=1 noshade color=dddddd>";

        $this->vardump_called = true;
    }

    // Alias for the above function
    public function dumpvar($mixed): void
    {
        $this->vardump($mixed);
    }

    // ==================================================================
    //  Displays the last query string that was sent to the database &
    //  a table listing results (if there were any).

    public function debug(): void
    {
        echo "<blockquote>";

        // Only show ezSQL credits once
        if (!$this->debug_called) {
            echo "<font color=800080 face=arial size=2><b>ezSQL</b> (v" . EZSQL_VERSION . ") <b>Debug..</b></font><p>\n";
        }
        echo "<font face=arial size=2 color=000099><b>Query</b> [" . $this->num_queries . "] <b>--</b> ";
        echo "[<font color=000000><b>" . htmlspecialchars((string) $this->last_query) . "</b></font>]</font><p>";

        echo "<font face=arial size=2 color=000099><b>Query Result..</b></font>";
        echo "<blockquote>";

        if (is_array($this->col_info) && $this->col_info !== []) {

            // Results top rows
            echo "<table cellpadding=5 cellspacing=1 bgcolor=555555>";
            echo "<tr bgcolor=eeeeee><td nowrap valign=bottom><font color=555599 face=arial size=2><b>(row)</b></font></td>";

            foreach ($this->col_info as $col) {
                $name  = htmlspecialchars((string) ($col->name ?? ''));
                $meta  = htmlspecialchars((string) ($col->type ?? ''));
                $maxlen = htmlspecialchars((string) ($col->max_length ?? ''));
                echo "<td nowrap align=left valign=top><font size=1 color=555599 face=arial>{$meta} {$maxlen}</font><br><span style='font-family: arial; font-size: 10pt; font-weight: bold;'>{$name}</span></td>";
            }

            echo "</tr>";

            // Print main results
            if (is_array($this->last_result)) {
                $i = 0;
                foreach ((array) $this->get_results(null, ARRAY_N) as $one_row) {
                    $i++;
                    echo "<tr bgcolor=ffffff><td bgcolor=eeeeee nowrap align=middle><font size=2 color=555599 face=arial>$i</font></td>";

                    foreach ($one_row as $item) {
                        echo "<td nowrap><font face=arial size=2>" . htmlspecialchars((string) $item) . "</font></td>";
                    }

                    echo "</tr>";
                }
            } else {
                echo "<tr bgcolor=ffffff><td colspan=" . (count($this->col_info) + 1) . "><font face=arial size=2>No Results</font></td></tr>";
            }

            echo "</table>";
        } else {
            echo "<font face=arial size=2>No Results</font>";
        }

        echo "</blockquote></blockquote>" . $this->donation() . "<hr noshade color=dddddd size=1>";

        $this->debug_called = true;
    }

    // =======================================================
    //  Donation link (disabled — returns empty string so string
    //  concatenation doesn't produce a null-deprecation notice)

    public function donation(): string
    {
        return "";
    }

}

$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
