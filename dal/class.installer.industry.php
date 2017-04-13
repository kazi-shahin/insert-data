<?php

class InstallerIndustry
{
    public $id;
    public $installer_id;
    public $industry_id;

    public function save()
    {
        global $wpdb;
        $confirm = $wpdb->insert(TableInstallerIndustry,
            array(
                'installer_id' => $this->installer_id,
                'industry_id' => $this->industry_id
            ),
            array(
                '%s',
                '%s'
            )
        );
        return $confirm;
    }


    public function deleteByInstallerId($installer_id)
    {
        global $wpdb;
        $where = array('installer_id' => $installer_id);
        return $wpdb->delete(TableInstallerIndustry, $where);
    }
}