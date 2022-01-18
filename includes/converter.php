<?php

// New Da'ud functions to keep things cleaner and together!

global $cnv_daud;
global $cnv_utf8;
global $cnv_ascii;
global $cnv_strip;
global $cnv_html;
global $cnv_western;
global $cnv_istvan;
global $cnv_rtfin;

$cnv_daud=array();
$cnv_utf8=array();
$cnv_ascii=array();
$cnv_strip=array();
$cnv_html=array();
$cnv_western=array();
$cnv_istvan=array();
$cnv_rtfin=array();


/*
 8 Convert from a hex code point (0x1234) to a character.
 */
function utf8($num) {
    if($num<=0x7F)     return chr($num);
    if($num<=0x7FF)    return chr(($num>>6)+192).chr(($num&63)+128);
    if($num<=0xFFFF)   return chr(($num>>12)+224).chr((($num>>6)&63)+128)
                .chr(($num&63)+128);
    if($num<=0x1FFFFF) return chr(($num>>18)+240).chr((($num>>12)&63)+128)
                .chr((($num>>6)&63)+128).chr(($num&63)+128);
    return '';
}

/*
 * Add a da'ud code and the associated encodings to the arrays.
 *
 * $d is the Da'ud code
 * $i is the Istvan code
 * $u is the UTF8 code
 * $w is the western-1/Latin-1/ISO-8859-1 code
 * $r is the RTF file code. RTF uses Latin-1, but ...
 * $h is the HTML code
 * Note that if there is not a Latin-1, the Da'ud should be used.
 */

function addOneDaud($d, $i, $u, $w, $r, $a, $h) {
	global $cnv_rtfin;
	global $cnv_daud;
	global $cnv_utf8;
	global $cnv_ascii;
	global $cnv_strip;
	global $cnv_html;
	global $cnv_western;
	global $cnv_istvan;

	$i  = str_replace(array("/", ":"), array("%", ";"), $i);
	array_push($cnv_daud, $d);
	array_push($cnv_istvan, $i);
	array_push($cnv_utf8, $u);
	array_push($cnv_western, $w);
	array_push($cnv_rtfin, $r);
	array_push($cnv_html, $h);
	array_push($cnv_ascii, $a);
}

function convert_from_utf_to_western($d) {
        return convert_from_utf8_to_western($d);
}

function convert_from_utf8_to_western($d) {
        global $cnv_western;
        global $cnv_utf8;
        return str_replace($cnv_utf8, $cnv_western, $d);
}

function convert_from_html_to_western($d) {
        global $cnv_western;
        global $cnv_html;
        return str_replace($cnv_html, $cnv_western, $d);
}

function convert_from_rtfin_to_daud($d) {
        global $cnv_daud;
        global $cnv_rtfin;
        return str_replace($cnv_rtfin, $cnv_daud,  $d);
}

function convert_from_rtfin_to_utf8($d) {
        global $cnv_utf8;
        global $cnv_rtfin;
        return str_replace($cnv_rtfin, $cnv_utf8,  $d);
}

function convert_from_rtfin_to_utf($d) {
        return convert_from_rtfin_to_utf8($d);
}

function convert_from_utf_to_daud($d) {
        global $cnv_daud;
        global $cnv_utf8;
        return str_replace($cnv_utf8, $cnv_daud,  $d);
}

function convert_from_utf8_to_daud($d) { 
	return convert_from_utf_to_daud($d); 
}

function convert_from_daud_to_utf8($d) {
        global $cnv_daud;
        global $cnv_utf8;

        if (get_magic_quotes_gpc()) { // pull the damned slashes off
                $d=str_replace("\\'", "'", $d);
                $d=str_replace("\\\"", "\"", $d);
                $d=str_replace("\\`", "`", $d);
        }
        $d = str_replace($cnv_daud, $cnv_utf8, $d);
        if (get_magic_quotes_gpc()) { // put 'em back!
                $d=str_replace("'", "\\'", $d);
                $d=str_replace("\"", "\\\"", $d);
                $d=str_replace("`", "\\`", $d);
        }
        return $d;
}

function convert_from_html_to_daud($d) {
        global $cnv_html;
        global $cnv_daud;
        return str_replace($cnv_html, $cnv_daud, $d);
}

function convert_from_html_to_utf8($d) {
        global $cnv_html;
        global $cnv_utf8;
        return str_replace($cnv_html, $cnv_utf8, $d);
}

function convert_from_html_to_ascii($d) {
        global $cnv_html;
        global $cnv_ascii;
        return str_replace($cnv_html, $cnv_ascii, $d);
}

function convert_from_daud_to_ascii($d) {
        global $cnv_daud;
        global $cnv_ascii;
        $hold = str_replace($cnv_daud, $cnv_ascii, $d);
        return str_replace("\'", "", $hold);
}

function convert_from_utf8_to_ascii($d) {
        global $cnv_utf8;
        global $cnv_ascii;
        $hold = str_replace($cnv_utf8, $cnv_ascii, $d);
        return str_replace("\'", "", $hold);
}

function convert_from_utf_to_ascii($d) { 
	return convert_from_utf8_to_ascii($d); 
}

function convert_from_utf8_to_html($d) {
        global $cnv_utf8;
        global $cnv_html;
        return str_replace($cnv_utf8, $cnv_html, $d);
}

function convert_from_utf_to_html($d) { 
	return  convert_from_utf8_to_html($d); 
}

function convert_from_western_to_daud($d) {
        global $cnv_western;
        global $cnv_daud;
        return str_replace($cnv_western, $cnv_daud, $d);
}

function convert_from_daud_to_western($d) {
        global $cnv_western;
        global $cnv_daud;
        return str_replace($cnv_daud, $cnv_western, $d);
}
function convert_from_western_to_utf8($d) {
        global $cnv_western;
        global $cnv_utf8;
        return str_replace($cnv_western, $cnv_utf8, $d);
}

function convert_from_western_to_ascii($d) {
        global $cnv_western;
        global $cnv_ascii;
        $hold = str_replace($cnv_western, $cnv_ascii, $d);
        return str_replace("\'", "", $hold);
}

function convert_from_istvan_to_utf8($d) {
        global $cnv_istvan;
        global $cnv_utf8;

        if (get_magic_quotes_gpc()) { // pull the damned slashes off
                $d=str_replace("\\'", "'", $d);
                $d=str_replace("\\\"", "\"", $d);
                $d=str_replace("\\`", "`", $d);
        }
        $d = str_replace($cnv_istvan, $cnv_utf8, $d);
        if (get_magic_quotes_gpc()) { // put 'em back!
                $d=str_replace("'", "\\'", $d);
                $d=str_replace("\"", "\\\"", $d);
                $d=str_replace("`", "\\`", $d);
        }
        return $d;
}

function convert_from_istvan_to_ascii($d) {
        global $cnv_istvan;
        global $cnv_ascii;
        $hold = str_replace($cnv_istvan, $cnv_ascii, $d);
        return str_replace("\'", "", $hold);
}

function convert_from_rtfin_to_istvan($d) {
        global $cnv_istvan;
        global $cnv_rtfin;
        return str_replace($cnv_rtfin, $cnv_istvan,  $d);
}

function convert_from_utf_to_istvan($d) {
        global $cnv_istvan;
        global $cnv_utf8;
        return str_replace($cnv_utf8, $cnv_istvan,  $d);
}

function convert_from_utf8_to_istvan($d) { 
	return convert_from_utf_to_istvan($d); 
}

function convert_from_western_to_istvan($d) {
        global $cnv_western;
        global $cnv_istvan;
        return str_replace($cnv_western, $cnv_istvan, $d);
}

function count_intl_characters($d, $charlist) {
        $count = 0;
        foreach ($charlist as $needle) {
                $pos = 0;
                if (FALSE === strpos($needle, "{")) {
                        while (FALSE !== ($pos = strpos($d, $needle, $pos))) {
                                $count ++;
                                $pos++;
                                if ($count>1000)
                                        return 1000;
                        }
                }
        }
        return $count;
}

function count_utf8($s) {
        global $cnv_utf8;
        return count_intl_characters($s, $cnv_utf8);
}


function count_western($s) {
        global $cnv_western;
        return count_intl_characters($s, $cnv_western);
}

function extra_strip($d)
{
		$r = convert_from_utf8_to_ascii($d);
	   $r = str_replace("'", "", $r);
	   $r = str_replace("`", "", $r);
	   return $r;
}


// TABLE STARTS HERE

// The order of this is identical to the order at 
//   http://oanda.sca.org/data_symbols.html 
// EXCEPT that A~ comes first - it screws other conversions up!

//           D       I        U           W      R       A    H
addOneDaud("{A~}", "{A~}", "\xc3\x83",   "\xc3", "\xc3", "A", "&Atilde;");

addOneDaud("{'A}", "{'A}", "\xc3\x80",   "\xc0", "\xc0", "A", "&Agrave;");
addOneDaud("{A'}", "{A'}", "\xc3\x81",   "\xc1", "\xc1", "A", "&Aacute;");
addOneDaud("{A^}", "{A^}", "\xc3\x82",   "\xc2", "\xc2", "A", "&Acirc;");
// A~ should come here. See above for why it's first.
addOneDaud("{A:}", "{A;}", "\xc3\x84",   "\xc4", "\xc4", "A", "&Auml;");
addOneDaud("{Ao}", "{Ao}", "\xc3\x85",   "\xc5", "\xc5", "Ao", "&Aring;");
addOneDaud("{AE}", "{AE}", "\xc3\x86",   "\xc6", "\xc6", "AE", "&AElig;");
addOneDaud("{C,}", "{C,}", "\xc3\x87",   "\xc7", "\xc7", "C", "&Ccedil;");
addOneDaud("{'E}", "{'E}", "\xc3\x88",   "\xc8", "\xc8", "E", "&Egrave;");
addOneDaud("{E'}", "{E'}", "\xc3\x89",   "\xc9", "\xc9", "E", "&Eacute;");
addOneDaud("{E^}", "{E^}", "\xc3\x8a",   "\xca", "\xca", "E", "&Ecirc;");
addOneDaud("{E:}", "{E;}", "\xc3\x8b",   "\xcb", "\xcb", "E", "&Euml;");
addOneDaud("{'I}", "{'I}", "\xc3\x8c",   "\xcc", "\xcc", "I", "&Igrave;");
addOneDaud("{I'}", "{I'}", "\xc3\x8d",   "\xcd", "\xcd", "I", "&Iacute;");
addOneDaud("{I^}", "{I^}", "\xc3\x8e",   "\xce", "\xce", "I", "&Icirc;");
addOneDaud("{I:}", "{I;}", "\xc3\x8f",   "\xcf", "\xcf", "I", "&Iuml;");
addOneDaud("{Dh}", "{Dh}", "\xc3\x90",   "\xd0", "\xd0", "Dh", "&ETH;");
addOneDaud("{N~}", "{N~}", "\xc3\x91",   "\xd1", "\xd1", "N", "&Ntilde;");
addOneDaud("{'O}", "{'O}", "\xc3\x92",   "\xd2", "\xd2", "O", "&Ograve");
addOneDaud("{O'}", "{O'}", "\xc3\x93",   "\xd3", "\xd3", "O", "&Oacute;");
addOneDaud("{O^}", "{O^}", "\xc3\x94",   "\xd4", "\xd4", "O", "&Ocirc;");
addOneDaud("{O~}", "{O~}", "\xc3\x95",   "\xd5", "\xd5", "O", "&Otilde;");
addOneDaud("{O:}", "{O;}", "\xc3\x96",   "\xd6", "\xd6", "O", "&Ouml;");
addOneDaud("{O/}", "{O%}", "\xc3\x98",   "\xd8", "\xd8", "O", "&Oslash;");
addOneDaud("{'U}", "{'U}", "\xc3\x99",   "\xd9", "\xd9", "U", "&Ugrave;");
addOneDaud("{U'}", "{U'}", "\xc3\x9a",   "\xda", "\xda", "U", "&Uacute;");
addOneDaud("{U^}", "{U^}", "\xc3\x9b",   "\xdb", "\xdb", "U", "&Ucirc;");
addOneDaud("{U:}", "{U;}", "\xc3\x9c",   "\xdc", "\xdc", "U", "&Uuml;");
addOneDaud("{Y'}", "{Y'}", "\xc3\x9d",   "\xdd", "\xdd", "Y", "&Yacute;");
addOneDaud("{Th}", "{Th}", "\xc3\x9e",   "\xde", "\xde", "Th", "&THORN;");
// sz moved due to encoding issues.
addOneDaud("{'a}", "{'a}", "\xc3\xa0",   "\xe0", "\xe0", "a", "&agrave;");
addOneDaud("{a'}", "{a'}", "\xc3\xa1",   "\xe1", "\xe1", "a", "&aacute;");
addOneDaud("{a^}", "{a^}", "\xc3\xa2",   "\xe2", "\xe2", "a", "&acirc;");
addOneDaud("{a~}", "{a~}", "\xc3\xa3",   "\xe3", "\xe3", "a", "&atilde;");
addOneDaud("{a:}", "{a:}", "\xc3\xa4",   "\xe4", "\xe4", "a", "&auml;");
addOneDaud("{ao}", "{ao}", "\xc3\xa5",   "\xe5", "\xe5", "ao", "&aring;");
addOneDaud("{ae}", "{ae}", "\xc3\xa6",   "\xe6", "\xe6", "ae", "&aelig;");
addOneDaud("{c,}", "{c,}", "\xc3\xa7",   "\xe7", "\xe7", "c", "&ccedil;");
addOneDaud("{'e}", "{'e}", "\xc3\xa8",   "\xe8", "\xe8", "e", "&egrave;");
addOneDaud("{e'}", "{e'}", "\xc3\xa9",   "\xe9", "\xe9", "e", "&eacute;");
addOneDaud("{e^}", "{e^}", "\xc3\xaa",   "\xea", "\xea", "e", "&ecirc;");
addOneDaud("{e:}", "{e:}", "\xc3\xab",   "\xeb", "\xeb", "e", "&euml;");
addOneDaud("{'i}", "{'i}", "\xc3\xac",   "\xec", "\xec", "i", "&igrave;");
addOneDaud("{i'}", "{i'}", "\xc3\xad",   "\xed", "\xed", "i", "&iacute;");
addOneDaud("{i^}", "{i^}", "\xc3\xae",   "\xee", "\xee", "i", "&icirc;");
//           D       I        U            W      R       A    H
addOneDaud("{i:}", "{i:}", "\xc3\xaf",   "\xef", "\xef", "i", "&iuml;");
addOneDaud("{dh}", "{dh}", "\xc3\xb0",   "\xf0", "\xf0", "dh", "&eth;");
addOneDaud("{n~}", "{n~}", "\xc3\xb1",   "\xf1", "\xf1", "n", "&ntilde;");
addOneDaud("{'o}", "{'o}", "\xc3\xb2",   "\xf2", "\xf2", "o", "&ograve;");
addOneDaud("{o'}", "{o'}", "\xc3\xb3",   "\xf3", "\xf3", "o", "&oacute;");
addOneDaud("{o^}", "{o^}", "\xc3\xb4",   "\xf4", "\xf4", "o", "&ocirc;");
addOneDaud("{o~}", "{o~}", "\xc3\xb5",   "\xf5", "\xf5", "o", "&otilde;");
addOneDaud("{o:}", "{o:}", "\xc3\xb6",   "\xf6", "\xf6", "o", "&ouml;");
addOneDaud("{o/}", "{o%}", "\xc3\xb8",   "\xf8", "\xf8", "o", "&oslash;");
addOneDaud("{'u}", "{'u}", "\xc3\xb9",   "\xf9", "\xf9", "u", "&ugrave;");
addOneDaud("{u'}", "{u'}", "\xc3\xba",   "\xfa", "\xfa", "u", "&uacute;");
addOneDaud("{u^}", "{u^}", "\xc3\xbb",   "\xfb", "\xfb", "u", "&ucirc;");
addOneDaud("{u:}", "{u:}", "\xc3\xbc",   "\xfc", "\xfc", "u", "&uuml;");
addOneDaud("{y'}", "{y'}", "\xc3\xbd",   "\xfd", "\xfd", "y", "&yacute;");
addOneDaud("{th}", "{th}", "\xc3\xbe",   "\xfe", "\xfe", "th", "&thorn;");
addOneDaud("{y:}", "{y:}", "\xc3\xbf",   "\xff", "\xff", "y", "&yuml;");
addOneDaud("{A-}", "{A-}", utf8(0x0100), "{A-}", "{A-}", "A", "{A-}");
addOneDaud("{a-}", "{a-}", "\xc4\x81",   "{a-}", "{a-}", "a", "{a-}");
addOneDaud("{Au}", "{Au}", utf8(0x0102), "{Au}", "{Au}", "A", "{Au}");
addOneDaud("{au}", "{au}", utf8(0x0103), "{au}", "{au}", "a", "{au}");
addOneDaud("{A,}", "{A,}", utf8(0x0104), "{A,}", "{A,}", "A", "{A,}");
addOneDaud("{a,}", "{a,}", utf8(0x0105), "{a,}", "{a,}", "a", "{a,}");
addOneDaud("{C'}", "{C'}", utf8(0x0106), "{C'}", "{C'}", "C", "{C'}");
addOneDaud("{c'}", "{c'}", utf8(0x0107), "{c'}", "{c'}", "c", "{c'}");
addOneDaud("{C^}", "{C^}", utf8(0x0108), "{C^}", "{C^}", "C", "{C^}");
addOneDaud("{c^}", "{c^}", utf8(0x0109), "{c^}", "{c^}", "c", "{c^}");
addOneDaud("{Cv}", "{Cv}", utf8(0x010C), "{Cv}", "{Cv}", "C", "{Cv}");
addOneDaud("{cv}", "{cv}", utf8(0x010D), "{cv}", "{cv}", "c", "{cv}");
addOneDaud("{Dv}", "{Dv}", utf8(0x010E), "{Dv}", "{Dv}", "D", "{Dv}");
addOneDaud("{dv}", "{dv}", utf8(0x010F), "{dv}", "{dv}", "d", "{dv}"); 
addOneDaud("{D/}", "{D/}", utf8(0x0110), "{D/}", "{D/}", "D", "{D/}");
addOneDaud("{d/}", "{d/}", utf8(0x0111), "{d/}", "{d/}", "d", "{d/}");
addOneDaud("{E-}", "{E-}", utf8(0x0112), "{E-}", "{E-}", "E", "{E-}");
addOneDaud("{e-}", "{e-}", utf8(0x0113), "{e-}", "{e-}", "e", "{e-}");
addOneDaud("{Eu}", "{Eu}", utf8(0x0114), "{Eu}", "{Eu}", "E", "{Eu}");
addOneDaud("{eu}", "{eu}", utf8(0x0115), "{eu}", "{eu}", "e", "{eu}");
addOneDaud("{.E}", "{.E}", utf8(0x0116), "{.E}", "{.E}", "E", "{.E}");
addOneDaud("{.e}", "{.e}", utf8(0x0117), "{.e}", "{.e}", "e", "{.e}");
addOneDaud("{E,}", "{E,}", utf8(0x0118), "{E,}", "{E,}", "E", "{E,}");
addOneDaud("{e,}", "{e,}", utf8(0x0119), "{e,}", "{e,}", "e", "{e,}");
addOneDaud("{Ev}", "{Ev}", utf8(0x011A), "{Ev}", "{Ev}", "E", "{Ev}");
addOneDaud("{ev}", "{ev}", utf8(0x011B), "{ev}", "{ev}", "e", "{ev}");
addOneDaud("{G^}", "{G^}", utf8(0x011C), "{G^}", "{G^}", "G", "{G^}");
addOneDaud("{g^}", "{g^}", utf8(0x011D), "{g^}", "{g^}", "g", "{g^}");
addOneDaud("{Gu}", "{Gu}", utf8(0x011E), "{Gu}", "{Gu}", "G", "{Gu}");
addOneDaud("{gu}", "{gu}", utf8(0x011F), "{gu}", "{gu}", "g", "{gu}");
addOneDaud("{G,}", "{G,}", utf8(0x0122), "{G,}", "{G,}", "G", "{G,}");
addOneDaud("{g,}", "{g,}", utf8(0x0123), "{g,}", "{g,}", "g", "{g,}");
addOneDaud("{H^}", "{H^}", utf8(0x0124), "{H^}", "{H^}", "H", "{H^}"); 
addOneDaud("{h^}", "{h^}", utf8(0x0125), "{h^}", "{h^}", "h", "{h^}");
addOneDaud("{H/}", "{H/}", utf8(0x0126), "{H/}", "{H/}", "H", "{H/}");
addOneDaud("{h/}", "{h/}", utf8(0x0127), "{h/}", "{h/}", "h", "{h/}");
addOneDaud("{I~}", "{I~}", utf8(0x0128), "{I~}", "{I~}", "I", "{I~}");
addOneDaud("{i~}", "{i~}", utf8(0x0129), "{i~}", "{i~}", "i", "{i~}");
addOneDaud("{I-}", "{I-}", utf8(0x012A), "{I-}", "{I-}", "I", "{I-}");
addOneDaud("{i-}", "{i-}", utf8(0x012B), "{i-}", "{i-}", "i", "{i-}");
addOneDaud("{Iu}", "{Iu}", utf8(0x012C), "{Iu}", "{Iu}", "I", "{Iu}");
addOneDaud("{iu}", "{iu}", utf8(0x012D), "{iu}", "{iu}", "i", "{iu}");
addOneDaud("{I,}", "{I,}", utf8(0x012E), "{I,}", "{I,}", "I", "{I,}");
addOneDaud("{i,}", "{i,}", utf8(0x012F), "{i,}", "{i,}", "i", "{i,}");
addOneDaud("{.I}", "{.I}", utf8(0x0130), "{.I}", "{.I}", "I", "{.I}");
addOneDaud("{i}",  "{i}",  utf8(0x0131), "{i}",  "{i}",  "i",  "{i}");
addOneDaud("{IJ}", "{IJ}", utf8(0x0132), "{IJ}", "{IJ}", "IJ", "{IJ}");
addOneDaud("{ij}", "{ij}", utf8(0x0133), "{ij}", "{ij}", "ij", "{ij}");
addOneDaud("{J^}", "{J^}", utf8(0x0134), "{J^}", "{J^}", "J", "{J^}");
addOneDaud("{j^}", "{j^}", utf8(0x0135), "{j^}", "{j^}", "j", "{j^}");
addOneDaud("{K,}", "{K,}", utf8(0x0136), "{K,}", "{K,}", "K", "{K,}");
addOneDaud("{k,}", "{k,}", utf8(0x0137), "{k,}", "{k,}", "k", "{k,}");
addOneDaud("{L'}", "{L'}", utf8(0x0139), "{L'}", "{L'}", "L", "{L'}");
addOneDaud("{l'}", "{l'}", utf8(0x013A), "{l'}", "{l'}", "l", "{l'}");
addOneDaud("{L,}", "{L,}", utf8(0x013B), "{L,}", "{L,}", "L", "{L,}");
addOneDaud("{l,}", "{l,}", utf8(0x013C), "{l,}", "{l,}", "l", "{l,}");
addOneDaud("{Lv}", "{Lv}", utf8(0x013D), "{Lv}", "{Lv}", "L", "{Lv}");
addOneDaud("{lv}", "{lv}", utf8(0x013E), "{lv}", "{lv}", "l", "{lv}");
addOneDaud("{L/}", "{L/}", utf8(0x0141), "{L/}", "{L/}", "L", "{L/}");
addOneDaud("{l/}", "{l/}", utf8(0x0142), "{l/}", "{l/}", "l", "{l/}");
addOneDaud("{N'}", "{N'}", utf8(0x0143), "{N'}", "{N'}", "N", "{N'}");
addOneDaud("{n'}", "{n'}", utf8(0x0144), "{n'}", "{n'}", "n", "{n'}");
addOneDaud("{N,}", "{N,}", utf8(0x0145), "{N,}", "{N,}", "N", "{N,}");
addOneDaud("{n,}", "{n,}", utf8(0x0146), "{n,}", "{n,}", "n", "{n,}");
addOneDaud("{Nv}", "{Nv}", utf8(0x0147), "{Nv}", "{Nv}", "N", "{Nv}");
addOneDaud("{nv}", "{nv}", utf8(0x0148), "{nv}", "{nv}", "n", "{nv}");
addOneDaud("{Ng}", "{Ng}", utf8(0x014A), "{Ng}", "{Ng}", "Ng", "{Ng}");
addOneDaud("{ng}", "{ng}", utf8(0x014B), "{ng}", "{ng}", "ng", "{ng}");
addOneDaud("{O-}", "{O-}", utf8(0x014C), "{O-}", "{O-}", "O", "{O-}");
addOneDaud("{o-}", "{o-}", utf8(0x014D), "{o-}", "{o-}", "o", "{o-}");
addOneDaud("{Ou}", "{Ou}", utf8(0x014E), "{Ou}", "{Ou}", "O", "{Ou}");
addOneDaud("{ou}", "{ou}", utf8(0x014F), "{ou}", "{ou}", "o", "{ou}");
addOneDaud("{O\"}", "{O#}", utf8(0x0150), "{O\"}", "{O\"}", "O", "{O\"}");//NW
addOneDaud("{o\"}", "{o#}", utf8(0x0151), "{o\"}", "{o\"}", "o", "{o\"}");//NW
addOneDaud("{OE}", "{OE}", utf8(0x0152), "\x8c", "\x8c", "OE", "{OE}");
addOneDaud("{oe}", "{oe}", utf8(0x0153), "\x9c", "\x9c", "oe", "{oe}");
addOneDaud("{R'}", "{R'}", utf8(0x0154), "{R'}", "{R'}", "R", "{R'}");
addOneDaud("{r'}", "{r'}", utf8(0x0155), "{r'}", "{r'}", "r", "{r'}");
addOneDaud("{R,}", "{R,}", utf8(0x0156), "{R,}", "{R,}", "R", "{R,}");
addOneDaud("{r,}", "{r,}", utf8(0x0157), "{r,}", "{r,}", "r", "{r,}");
addOneDaud("{Rv}", "{Rv}", utf8(0x0158), "{Rv}", "{Rv}", "R", "{Rv}");
addOneDaud("{rv}", "{rv}", utf8(0x0159), "{rv}", "{rv}", "r", "{rv}");
addOneDaud("{S'}", "{S'}", utf8(0x015A), "{S'}", "{S'}", "S", "{S'}");
addOneDaud("{s'}", "{s'}", utf8(0x015B), "{s'}", "{s'}", "s", "{s'}");
addOneDaud("{S^}", "{S^}", utf8(0x015C), "{S^}", "{S^}", "S", "{S^}");
addOneDaud("{s^}", "{s^}", utf8(0x015D), "{s^}", "{s^}", "s", "{s^}");
addOneDaud("{S,}", "{S,}", utf8(0x015E), "{S,}", "{S,}", "S", "{S,}");
addOneDaud("{s,}", "{s,}", utf8(0x015F), "{s,}", "{s,}", "s", "{s,}");
//           D       I        U          W      R       A    H
addOneDaud("{Sv}", "{Sv}", utf8(0x0160), "\x8a", "\x8a", "S", "&Scaron;");
addOneDaud("{sv}", "{sv}", utf8(0x0161), "\x9a", "\x9a", "s", "&scaron;");
addOneDaud("{T,}", "{T,}", utf8(0x0162), "{T,}", "{T,}", "T", "{T,}");
addOneDaud("{t,}", "{t,}", utf8(0x0163), "{t,}", "{t,}", "t", "{t,}");
addOneDaud("{Tv}", "{Tv}", utf8(0x0164), "{Tv}", "{Tv}", "T", "{Tv}");
addOneDaud("{tv}", "{tv}", utf8(0x0165), "{tv}", "{tv}", "t", "{tv}"); 
addOneDaud("{T/}", "{T/}", utf8(0x0166), "{T/}", "{T/}", "T", "{T/}");
addOneDaud("{t/}", "{t/}", utf8(0x0167), "{t/}", "{t/}", "t", "{t/}");
addOneDaud("{U~}", "{U~}", utf8(0x0168), "{U~}", "{U~}", "U", "{U~}");
addOneDaud("{u~}", "{u~}", utf8(0x0169), "{u~}", "{u~}", "u", "{u~}");
addOneDaud("{U-}", "{U-}", utf8(0x016A), "{U-}", "{U-}", "U", "{U-}");
addOneDaud("{u-}", "{u-}", utf8(0x016B), "{u-}", "{u-}", "u", "{u-}");
addOneDaud("{Uu}", "{Uu}", utf8(0x016C), "{Uu}", "{Uu}", "U", "{Uu}");
addOneDaud("{uu}", "{uu}", utf8(0x016D), "{uu}", "{uu}", "u", "{uu}");
addOneDaud("{Uo}", "{Uo}", utf8(0x016E), "{Uo}", "{Uo}", "U", "{Uo}");
addOneDaud("{uo}", "{uo}", utf8(0x016F), "{uo}", "{uo}", "u", "{uo}");
addOneDaud("{U\"}", "{U#}", utf8(0x0170), "{U\"}", "{U\"}", "U", "{U\"}");//NW
addOneDaud("{u\"}", "{u#}", utf8(0x0171), "{u\"}", "{u\"}", "u", "{u\"}");//NW
addOneDaud("{U,}", "{U,}", utf8(0x0172), "{U,}", "{U,}", "U", "{U,}");
addOneDaud("{u,}", "{u,}", utf8(0x0173), "{u,}", "{u,}", "u", "{u,}");
addOneDaud("{W^}", "{W^}", utf8(0x0174), "{W^}", "{W^}", "W", "{W^}");
addOneDaud("{w^}", "{w^}", utf8(0x0175), "{w^}", "{w^}", "w", "{w^}");
addOneDaud("{Y^}", "{Y^}", utf8(0x0176), "{Y^}", "{Y^}", "Y", "{Y^}");
addOneDaud("{y^}", "{y^}", utf8(0x0177), "{y^}", "{y^}", "y", "{y^}");
//           D       I        U          W      R       A    H
addOneDaud("{Y:}", "{Y:}", utf8(0x0178), "\x9f", "\x9f", "Y", "&Yuml;");
addOneDaud("{Z'}", "{Z'}", utf8(0x0179), "{Z'}", "{Z'}", "Z", "{Z'}");
addOneDaud("{z'}", "{z'}", utf8(0x017A), "{z'}", "{z'}", "z", "{z'}");
addOneDaud("{.Z}", "{.Z}", utf8(0x017B), "{.Z}", "{.Z}", "Z", "{.Z}");
addOneDaud("{.z}", "{.z}", utf8(0x017C), "{.z}", "{.z}", "z", "{.z}");
addOneDaud("{Zv}", "{Zv}", utf8(0x017D), "\x8e", "\x8e", "Z", "{Zv}");
addOneDaud("{zv}", "{zv}", utf8(0x017E), "\x9e", "\x93", "z", "&zcaron");
addOneDaud("{b/}", "{b/}", utf8(0x0180), "{b/}", "{b/}", "b", "{b/}");
addOneDaud("{B-}", "{B-}", utf8(0x0182), "{B-}", "{B-}", "Bh", "{B-}");
addOneDaud("{b-}", "{b-}", utf8(0x0183), "{b-}", "{b-}", "bh", "{b-}");
addOneDaud("{D-}", "{D-}", utf8(0x018B), "{D-}", "{D-}", "Dh", "{D-}");
addOneDaud("{d-}", "{d-}", utf8(0x018C), "{d-}", "{d-}", "dh", "{d-}");
addOneDaud("{I/}", "{I/}", utf8(0x0197), "{I/}", "{I/}", "I", "{I/}");
addOneDaud("{Z/}", "{Z/}", utf8(0x01B5), "{Z/}", "{Z/}", "Z", "{Z/}");
addOneDaud("{z/}", "{z/}", utf8(0x01B6), "{z/}", "{z/}", "z", "{z/}");
addOneDaud("{Zh}", "{Zh}", utf8(0x01B7), "{Zh}", "{Zh}", "Zh", "{Zh}");
addOneDaud("{Av}", "{Av}", utf8(0x01CD), "{Av}", "{Av}", "A", "{Av}");
addOneDaud("{av}", "{av}", utf8(0x01CE), "{av}", "{av}", "a", "{av}");
addOneDaud("{Iv}", "{Iv}", utf8(0x01CF), "{Iv}", "{Iv}", "I", "{Iv}");
addOneDaud("{iv}", "{iv}", utf8(0x01D0), "{iv}", "{iv}", "i", "{iv}");
addOneDaud("{Ov}", "{Ov}", utf8(0x01D1), "{Ov}", "{Ov}", "O", "{Ov}");
addOneDaud("{ov}", "{ov}", utf8(0x01D2), "{ov}", "{ov}", "o", "{ov}");
addOneDaud("{Uv}", "{Uv}", utf8(0x01D3), "{Uv}", "{Uv}", "U", "{Uv}");
addOneDaud("{uv}", "{uv}", utf8(0x01D4), "{uv}", "{uv}", "u", "{uv}");
addOneDaud("{G/}", "{G/}", utf8(0x01E4), "{G/}", "{G/}", "G", "{G/}");
addOneDaud("{g/}", "{g/}", utf8(0x01E5), "{g/}", "{g/}", "g", "{g/}");
addOneDaud("{Gv}", "{Gv}", utf8(0x01E6), "{Gv}", "{Gv}", "G", "{Gv}");
addOneDaud("{gv}", "{gv}", utf8(0x01E7), "{gv}", "{gv}", "g", "{gv}");
addOneDaud("{Kv}", "{Kv}", utf8(0x01E8), "{Kv}", "{Kv}", "K", "{Kv}");
addOneDaud("{kv}", "{kv}", utf8(0x01E9), "{kv}", "{kv}", "k", "{kv}");
addOneDaud("{O,}", "{O,}", utf8(0x01EA), "{O,}", "{O,}", "O", "{O,}");
addOneDaud("{o,}", "{o,}", utf8(0x01EB), "{o,}", "{o,}", "o", "{o,}");
addOneDaud("{jv}", "{jv}", utf8(0x01F0), "{jv}", "{jv}", "j", "{jv}");
addOneDaud("{G'}", "{G'}", utf8(0x01F4), "{G'}", "{G'}", "G", "{G'}");
addOneDaud("{g'}", "{g'}", utf8(0x01F5), "{g'}", "{g'}", "g", "{g'}");
addOneDaud("{\"A}", "{\"A}", utf8(0x0200), "{\"A}", "{\"A}", "A", "{\"A}");//NW
addOneDaud("{\"a}", "{\"a}", utf8(0x0201), "{\"a}", "{\"a}", "a", "{\"a}");//NW
addOneDaud("{An}", "{An}", utf8(0x0202), "{An}", "{An}", "A", "{An}");
addOneDaud("{an}", "{an}", utf8(0x0203), "{an}", "{an}", "a", "{an}");
addOneDaud("{\"E}", "{\"E}", utf8(0x0204), "{\"E}", "{\"E}", "E", "{\"E}");//NW
addOneDaud("{\"e}", "{\"e}", utf8(0x0205), "{\"e}", "{\"e}", "e", "{\"e}");//NW
addOneDaud("{En}", "{En}", utf8(0x0206), "{En}", "{En}", "E", "{En}");
addOneDaud("{en}", "{en}", utf8(0x0207), "{en}", "{en}", "e", "{en}");
addOneDaud("{\"I}", "{\"I}", utf8(0x0208), "{\"I}", "{\"I}", "I", "{\"I}");//NW
addOneDaud("{\"i}", "{\"i}", utf8(0x0209), "{\"i}", "{\"i}", "i", "{\"i}");//NW
addOneDaud("{In}", "{In}", utf8(0x020A), "{In}", "{In}", "I", "{In}");
addOneDaud("{in}", "{in}", utf8(0x020B), "{in}", "{in}", "i", "{in}");
addOneDaud("{\"O}", "{\"O}", utf8(0x020C), "{\"O}", "{\"O}", "O", "{\"O}");//NW
addOneDaud("{\"o}", "{\"o}", utf8(0x020D), "{\"o}", "{\"o}", "o", "{\"o}");//NW
addOneDaud("{On}", "{On}", utf8(0x020E), "{On}", "{On}", "O", "{On}");
addOneDaud("{on}", "{on}", utf8(0x020F), "{on}", "{on}", "o", "{on}");
addOneDaud("{\"R}", "{\"R}", utf8(0x0210), "{\"R}", "{\"R}", "R", "{\"R}");//NW
addOneDaud("{\"r}", "{\"r}", utf8(0x0211), "{\"r}", "{\"r}", "r", "{\"r}");//NW
addOneDaud("{Rn}", "{Rn}", utf8(0x0212), "{Rn}", "{Rn}", "R", "{Rn}");
addOneDaud("{rn}", "{rn}", utf8(0x0213), "{rn}", "{rn}", "r", "{rn}");
addOneDaud("{\"U}", "{\"U}", utf8(0x0214), "{\"U}", "{\"U}", "U", "{\"U}");//NW
addOneDaud("{\"u}", "{\"u}", utf8(0x0215), "{\"u}", "{\"u}", "u", "{\"u}");//NW
addOneDaud("{Un}", "{Un}", utf8(0x0216), "{Un}", "{Un}", "U", "{Un}");
addOneDaud("{un}", "{un}", utf8(0x0217), "{un}", "{un}", "u", "{un}");
addOneDaud("{Gh}", "{Gh}", utf8(0x021C), "{Gh}", "{Gh}", "3", "{Gh}");
addOneDaud("{3}",  "{3}" , utf8(0x021D), "{3}",  "{3}",  "3", "{3}");
addOneDaud("{gh}", "{gh}", utf8(0x021D), "{gh}", "{gh}", "3", "{gh}");
addOneDaud("{i/}", "{i/}", utf8(0x0268), "{i/}", "{i/}", "i", "{i/}");
addOneDaud("{zh}", "{zh}", utf8(0x0292), "{zh}", "{zh}", "zh", "{zh}");
addOneDaud("{Ph}", "{Ph}", utf8(0x03A6), "{Ph}", "{Ph}", "Ph", "{Ph}"); 
addOneDaud("{Ps}", "{Ps}", utf8(0x03A8), "{Ps}", "{Ps}", "Ps", "{Ps}");
addOneDaud("{B.}", "{B.}", utf8(0x1E04), "{B.}", "{B.}", "B", "{B.}");
addOneDaud("{b.}", "{b.}", utf8(0x1E05), "{b.}", "{b.}", "b", "{b.}");
addOneDaud("{B_}", "{B_}", utf8(0x1E06), "{B_}", "{B_}", "B", "{B_}");
addOneDaud("{b_}", "{b_}", utf8(0x1E07), "{b_}", "{b_}", "b", "{b_}");
addOneDaud("{D.}", "{D.}", utf8(0x1E0C), "{D.}", "{D.}", "D", "{D.}");
addOneDaud("{d.}", "{d.}", utf8(0x1E0D), "{d.}", "{d.}", "d", "{d.}");
addOneDaud("{D_}", "{D_}", utf8(0x1E0E), "{D_}", "{D_}", "D", "{D_}");
addOneDaud("{d_}", "{d_}", utf8(0x1E0F), "{d_}", "{d_}", "d", "{d_}");
addOneDaud("{D,}", "{D,}", utf8(0x1E10), "{D,}", "{D,}", "D", "{D,}"); 
addOneDaud("{d,}", "{d,}", utf8(0x1E11), "{d,}", "{d,}", "d", "{d,}");
addOneDaud("{G-}", "{G-}", utf8(0x1E20), "{G-}", "{G-}", "G", "{G-}");
addOneDaud("{g-}", "{g-}", utf8(0x1E21), "{g-}", "{g-}", "g", "{g-}");
addOneDaud("{H.}", "{H.}", utf8(0x1E24), "{H.}", "{H.}", "H", "{H.}");
addOneDaud("{h.}", "{h.}", utf8(0x1E25), "{h.}", "{h.}", "h", "{h.}");
addOneDaud("{H:}", "{H:}", utf8(0x1E26), "{H:}", "{H:}", "H", "{H:}");
addOneDaud("{h:}", "{h:}", utf8(0x1E27), "{h:}", "{h:}", "h", "{h:}");
addOneDaud("{H,}", "{H,}", utf8(0x1E28), "{H,}", "{H,}", "H", "{H,}");
addOneDaud("{h,}", "{h,}", utf8(0x1E29), "{h,}", "{h,}", "h", "{h,}");
//           D       I        U          W      R       A    H
addOneDaud("{K'}", "{K'}", utf8(0x1E30), "{K'}", "{K'}", "K", "{K'}");
addOneDaud("{k'}", "{k'}", utf8(0x1E31), "{k'}", "{k'}", "k", "{k'}");
addOneDaud("{K.}", "{K.}", utf8(0x1E32), "{K.}", "{K.}", "K", "{K.}");
addOneDaud("{k.}", "{k.}", utf8(0x1E33), "{k.}", "{k.}", "k", "{k.}");
addOneDaud("{K_}", "{K_}", utf8(0x1E34), "{K_}", "{K_}", "K", "{K_}");
addOneDaud("{k_}", "{k_}", utf8(0x1E35), "{k_}", "{k_}", "k", "{k_}");
addOneDaud("{L.}", "{L.}", utf8(0x1E36), "{L.}", "{L.}", "L", "{L.}");
addOneDaud("{l.}", "{l.}", utf8(0x1E37), "{l.}", "{l.}", "l", "{l.}");
addOneDaud("{L_}", "{L_}", utf8(0x1E3A), "{L_}", "{L_}", "L", "{L_}");
addOneDaud("{l_}", "{l_}", utf8(0x1E3B), "{l_}", "{l_}", "l", "{l_}");
addOneDaud("{M'}", "{M'}", utf8(0x1E3E), "{M'}", "{M'}", "M", "{M'}");
addOneDaud("{m'}", "{m'}", utf8(0x1E3F), "{m'}", "{m'}", "m", "{m'}");
addOneDaud("{M.}", "{M.}", utf8(0x1E42), "{M.}", "{M.}", "M", "{M.}");
addOneDaud("{m.}", "{m.}", "\xe1\xb9\x83", "{m.}", "{m.}", "m", "{m.}");
addOneDaud("{N.}", "{N.}", utf8(0x1E46), "{N.}", "{N.}", "N", "{N.}");
addOneDaud("{n.}", "{n.}", utf8(0x1E47), "{n.}", "{n.}", "n", "{n.}");
addOneDaud("{N_}", "{N_}", utf8(0x1E48), "{N_}", "{N_}", "N", "{N_}");
addOneDaud("{n_}", "{n_}", utf8(0x1E49), "{n_}", "{n_}", "n", "{n_}");
addOneDaud("{P'}", "{P'}", utf8(0x1E54), "{P'}", "{P'}", "P", "{P'}");
addOneDaud("{p'}", "{p'}", utf8(0x1E55), "{p'}", "{p'}", "p", "{p'}");
addOneDaud("{R.}", "{R.}", utf8(0x1E5A), "{R.}", "{R.}", "R", "{R.}");
addOneDaud("{r.}", "{r.}", utf8(0x1E5B), "{r.}", "{r.}", "r", "{r.}");
addOneDaud("{R_}", "{R_}", utf8(0x1E5E), "{R_}", "{R_}", "R", "{R_}");
addOneDaud("{r_}", "{r_}", utf8(0x1E5F), "{r_}", "{r_}", "r", "{r_}");
//           D       I        U          W      R       A    H
addOneDaud("{S.}", "{S.}", utf8(0x1E62), "{S.}", "{S.}", "S", "{S.}");
addOneDaud("{T.}", "{T.}", utf8(0x1E6C), "{T.}", "{T.}", "T", "{T.}");
addOneDaud("{t.}", "{t.}", utf8(0x1E6D), "{t.}", "{t.}", "t", "{t.}");
addOneDaud("{T_}", "{T_}", utf8(0x1E6E), "{T_}", "{T_}", "T", "{T_}");
addOneDaud("{t_}", "{t_}", utf8(0x1E6F), "{t_}", "{t_}", "t", "{t_}");
addOneDaud("{V~}", "{V~}", utf8(0x1E7C), "{V~}", "{V~}", "V", "{V~}");
addOneDaud("{v~}", "{v~}", utf8(0x1E7D), "{v~}", "{v~}", "v", "{v~}");
addOneDaud("{V.}", "{V.}", utf8(0x1E7E), "{V.}", "{V.}", "V", "{V.}");
addOneDaud("{v.}", "{v.}", utf8(0x1E7F), "{v.}", "{v.}", "v", "{v.}");
addOneDaud("{'W}", "{'W}", utf8(0x1E80), "{'W}", "{'W}", "W", "{'W}");
addOneDaud("{'w}", "{'w}", utf8(0x1E81), "{'w}", "{'w}", "w", "{'w}");
addOneDaud("{W'}", "{W'}", utf8(0x1E82), "{W'}", "{W'}", "W", "{W'}");
addOneDaud("{w'}", "{w'}", utf8(0x1E83), "{w'}", "{w'}", "w", "{w'}");
addOneDaud("{W:}", "{W:}", utf8(0x1E84), "{W:}", "{W:}", "W", "{W:}");
addOneDaud("{w:}", "{w:}", utf8(0x1E85), "{w:}", "{w:}", "w", "{w:}");
addOneDaud("{W.}", "{W.}", utf8(0x1E88), "{W.}", "{W.}", "W", "{W.}");
addOneDaud("{w.}", "{w.}", utf8(0x1E89), "{w.}", "{w.}", "w", "{w.}");
addOneDaud("{X:}", "{X:}", utf8(0x1E8C), "{X:}", "{X:}", "X", "{X:}");
addOneDaud("{x:}", "{x:}", utf8(0x1E8D), "{x:}", "{x:}", "x", "{x:}");
addOneDaud("{Z^}", "{Z^}", utf8(0x1E90), "{Z^}", "{Z^}", "Z", "{Z^}");
addOneDaud("{z^}", "{z^}", utf8(0x1E91), "{z^}", "{z^}", "z", "{z^}");
addOneDaud("{Z.}", "{Z.}", utf8(0x1E92), "{Z.}", "{Z.}", "Z", "{Z.}");
//           D       I        U          W      R       A    H
addOneDaud("{z.}", "{z.}", utf8(0x1E93), "{z.}", "{z.}", "z", "{z.}");
addOneDaud("{Z_}", "{Z_}", utf8(0x1E94), "{Z_}", "{Z_}", "Z", "{Z_}");
addOneDaud("{z_}", "{z_}", utf8(0x1E95), "{z_}", "{z_}", "z", "{z_}");
addOneDaud("{h_}", "{h_}", utf8(0x1E96), "{h_}", "{h_}", "h", "{h_}");
addOneDaud("{t:}", "{t:}", utf8(0x1E97), "{t:}", "{t:}", "t", "{t:}");
addOneDaud("{wo}", "{wo}", utf8(0x1E98), "{wo}", "{wo}", "w", "{wo}");
addOneDaud("{yo}", "{yo}", utf8(0x1E99), "{yo}", "{yo}", "y", "{yo}");
addOneDaud("{A.}", "{A.}", utf8(0x1EA0), "{A.}", "{A.}", "A", "{A.}");
addOneDaud("{a.}", "{a.}", utf8(0x1EA1), "{a.}", "{a.}", "a", "{a.}");
addOneDaud("{E.}", "{E.}", utf8(0x1EB8), "{E.}", "{E.}", "E", "{E.}");
addOneDaud("{e.}", "{e.}", utf8(0x1EB9), "{e.}", "{e.}", "e", "{e.}");
addOneDaud("{E~}", "{E~}", utf8(0x1EBC), "{E~}", "{E~}", "E", "{E~}");
addOneDaud("{e~}", "{e~}", utf8(0x1EBD), "{e~}", "{e~}", "e", "{e~}");
addOneDaud("{I.}", "{I.}", utf8(0x1ECA), "{I.}", "{I.}", "i", "{I.}");
addOneDaud("{i.}", "{i.}", utf8(0x1ECB), "{i.}", "{i.}", "i", "{i.}");
addOneDaud("{O.}", "{O.}", utf8(0x1ECC), "{O.}", "{O.}", "O", "{O.}");
addOneDaud("{o.}", "{o.}", utf8(0x1ECD), "{o.}", "{o.}", "o", "{o.}");
addOneDaud("{U.}", "{U.}", utf8(0x1EE4), "{U.}", "{U.}", "U", "{U.}");
addOneDaud("{u.}", "{u.}", utf8(0x1EE5), "{u.}", "{u.}", "u", "{u.}");
addOneDaud("{'Y}", "{'Y}", utf8(0x1EF2), "{'Y}", "{'Y}", "Y", "{'Y}");
addOneDaud("{'y}", "{'y}", utf8(0x1EF3), "{'y}", "{'y}", "y", "{'y}");
addOneDaud("{Y.}", "{Y.}", utf8(0x1EF4), "{Y.}", "{Y.}", "Y", "{Y.}");
addOneDaud("{y.}", "{y.}", utf8(0x1EF5), "{y.}", "{y.}", "y", "{y.}");
addOneDaud("{Y~}", "{Y~}", utf8(0x1EF8), "{Y~}", "{Y~}", "Y", "{Y~}");
//           D       I        U          W      R       A    H
addOneDaud("{y~}", "{y~}", "\xe1\xbb\xb9", "{y~}", "{y~}", "y", "{y~}");
addOneDaud("{ff}", "{ff}", utf8(0xFB00), "{ff}", "{ff}", "ff", "{ff}");
addOneDaud("{fi}", "{fi}", utf8(0xFB01), "{fi}", "{fi}", "fi", "{fi}");
addOneDaud("{fl}", "{fl}", utf8(0xFB02), "{fl}", "{fl}", "fl", "{fl}");
addOneDaud("{st}", "{st}", utf8(0xFB06), "{st}", "{st}", "st", "{st}");
addOneDaud("{u!}", "{u!}", utf8(0xE724), "{u!}", "{u!}", "u", "&uvertline;");
//           D       I        U          W      R       A    H

// repeat a few.
addOneDaud("{TH}", "{TH}", "\xc3\x9e",   "\xde", "\xde", "Th", "&THORN;");
addOneDaud("{DH}", "{DH}", "\xc3\x90",   "\xd0", "\xd0", "Dh", "&ETH;");

// space is weird in Istvan encoding.
addOneDaud(" ", "_", " ",   " ", " ", " ", " ");

// moved due to conflicts with Y:
addOneDaud("{sz}", "{sz}", "\xc3\x9f",   "\xdf", "\xdf", "sz", "&szlig;");

?>
