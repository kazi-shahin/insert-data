<?php
class InstallerProduct{
    public $id;
    public $installer_id;
    public $product_id;

    public function save()
    {
        global $wpdb;
        $confirm = $wpdb->insert(TableInstallerProduct,
            array(
                'installer_id' => $this->installer_id,
                'product_id' => $this->product_id
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
        return $wpdb->delete(TableInstallerProduct, $where);
    }
}