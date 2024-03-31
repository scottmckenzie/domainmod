<?php
/**
 * /_includes/config.SAMPLE.inc.php
 *
 * This file is part of DomainMOD, an open source domain and internet asset manager.
 * Copyright (c) 2010-2024 Greg Chetcuti <greg@chetcuti.com>
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
// Path Settings
$web_root = getenv('DOMAINMOD_WEB_ROOT') ?: '';

// Database Settings
$dbhostname = getenv('DOMAINMOD_DB_HOSTNAME');
$dbname = getenv('DOMAINMOD_DB_NAME');
$dbusername = getenv('DOMAINMOD_DB_USERNAME');
$dbpassword = getenv('DOMAINMOD_DB_PASSWORD');
$dbsslca = getenv('DOMAINMOD_DB_SSL_CA');
$dbsslcapath = getenv('DOMAINMOD_DB_SSL_CAPATH');
$dbsslverifycert = getenv('DOMAINMOD_DB_SSL_VERIFY_CERT');
