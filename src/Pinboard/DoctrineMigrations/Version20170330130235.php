<?php

namespace Pinboard\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170330130235 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql(
            "
            CREATE TABLE IF NOT EXISTS `ipm_pinba_report_by_hostname_and_server_and_script_90_95_99` (
              `req_count` int(11) DEFAULT NULL,
              `req_per_sec` float DEFAULT NULL,
              `req_time_total` float DEFAULT NULL,
              `req_time_percent` float DEFAULT NULL,
              `req_time_per_sec` float DEFAULT NULL,
              `ru_utime_total` float DEFAULT NULL,
              `ru_utime_percent` float DEFAULT NULL,
              `ru_utime_per_sec` float DEFAULT NULL,
              `ru_stime_total` float DEFAULT NULL,
              `ru_stime_percent` float DEFAULT NULL,
              `ru_stime_per_sec` float DEFAULT NULL,
              `traffic_total` float DEFAULT NULL,
              `traffic_percent` float DEFAULT NULL,
              `traffic_per_sec` float DEFAULT NULL,
              `hostname` varchar(32) DEFAULT NULL,
              `server_name` varchar(64) DEFAULT NULL,
              `script_name` varchar(128) DEFAULT NULL,
              `memory_footprint_total` float DEFAULT NULL,
              `memory_footprint_percent` float DEFAULT NULL,
              `req_time_median` float DEFAULT NULL,
              `index_value` varchar(256) DEFAULT NULL,
              `p90` float DEFAULT NULL,
              `p95` float DEFAULT NULL,
              `p99` float DEFAULT NULL
            ) ENGINE=PINBA DEFAULT CHARSET=latin1 COMMENT='report7:::90,95,99';
        "
        );
        $this->addSql(
            "
            CREATE TABLE IF NOT EXISTS `ipm_report_by_hostname_and_server_and_script` (
              `req_count` int(11) DEFAULT NULL,
              `req_per_sec` float DEFAULT NULL,
              `req_time_total` float DEFAULT NULL,
              `req_time_percent` float DEFAULT NULL,
              `req_time_per_sec` float DEFAULT NULL,
              `ru_utime_total` float DEFAULT NULL,
              `ru_utime_percent` float DEFAULT NULL,
              `ru_utime_per_sec` float DEFAULT NULL,
              `ru_stime_total` float DEFAULT NULL,
              `ru_stime_percent` float DEFAULT NULL,
              `ru_stime_per_sec` float DEFAULT NULL,
              `traffic_total` float DEFAULT NULL,
              `traffic_percent` float DEFAULT NULL,
              `traffic_per_sec` float DEFAULT NULL,
              `hostname` varchar(32) DEFAULT NULL,
              `server_name` varchar(64) DEFAULT NULL,
              `script_name` varchar(128) DEFAULT NULL,
              `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
              `req_time_median` float DEFAULT NULL,
              `p90` float DEFAULT NULL,
              `p95` float DEFAULT NULL,
              `p99` float DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ipm_report7';
        "
        );
        $this->addSql(
            "
            ALTER TABLE `ipm_report_by_hostname_and_server_and_script` ADD INDEX `irhas_sn_hn` (`server_name`, `hostname`, `script_name`);
        "
        );
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `ipm_pinba_report_by_hostname_and_server_and_script_90_95_99`");
        $this->addSql("DROP TABLE `ipm_report_by_hostname_and_server_and_script`");
    }
}
