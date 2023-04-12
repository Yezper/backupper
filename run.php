<?php

require 'vendor/autoload.php';

use Yezper\Backupper\Backup;

return (new Backup())->run();
