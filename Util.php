<?php

namespace Utilities;

class CryptoUtilities {
    public function getToken($length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->cryptoRandSecure(0, $max)];
        }
        return $token;
    }

    public function cryptoRandSecure($min, $max) {
        $range = $max - $min;
        if ($range < 1) {
            return $min; // not so random...
        }
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }
}

trait Redirect {
    public function redirect($url) {
        header("Location:" . $url);
        exit;
    }
}

class BasicUtilities {

    use Redirect;
    public function clearAuthCookie() {
        if (isset($_COOKIE["member_login"])) {
            setcookie("member_login", "");
        }
        if (isset($_COOKIE["random_password"])) {
            setcookie("random_password", "");
        }
        if (isset($_COOKIE["random_selector"])) {
            setcookie("random_selector", "");
        }
    }
    static public function render_table_markup($table_cols) {
        echo '<table class="table table-bordered table-striped">';
        echo "<thead>";
        echo "<tr>";
        for ($i = 0; $i < count($table_cols); $i++) {
            echo "<th>$table_cols[$i]</th>";
        }
        echo "</tr>";
        echo "</thead>";
    }
    static public function render_read_crud_data($headings_arr, $row, $col_names) {
        for ($i = 1; $i < count($headings_arr); $i++) {
            echo "<div class='form-group'>";
            echo '<label>' . $headings_arr[$i] . '</label>';
            echo '<p><b>' . $row[0][$col_names[$i]]  . '</b></p>';
            echo "</div>";
        }
    }
}
