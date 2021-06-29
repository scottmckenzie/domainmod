<?php
/**
 * /_includes/head.inc.php
 *
 * This file is part of DomainMOD, an open source domain and internet asset manager.
 * Copyright (c) 2010-2021 Greg Chetcuti <greg@chetcuti.com>
 *
 * Project: http://domainmod.org   Author: http://chetcuti.com
 *
 * DomainMOD is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * DomainMOD is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with DomainMOD. If not, see
 * http://www.gnu.org/licenses/.
 *
 */
?>
<?php
header('Content-Type: text/html; charset=utf-8');

// Add $disable_csp = 1; to /_includes/config.inc.php to disable the Content Security Policy headers
if ($disable_csp !== 1) {

    header("Content-Security-Policy: default-src 'self';");
    header("Content-Security-Policy: form-action 'self';");
    header("Content-Security-Policy: frame-ancestors 'none';");
    header("Content-Security-Policy: img-src 'self';");
    header("Content-Security-Policy: object-src 'none';");
    header("Content-Security-Policy: style-src-elem 'self' code.ionicframework.com fonts.googleapis.com maxcdn.bootstrapcdn.com;");

}

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header("X-XSS-Protection: 1; mode=block");
