<?php 
/**
 * License page is a place where a user can check updated and manage the license.
 */
class OPanda_LicenseManagerPage extends OnpLicensing325_LicenseManagerPage  {
 
    public $purchasePrice = '$23';
    
    public function configure() {
                $this->purchasePrice = '$23'; global $optinpanda;
if ( in_array( $optinpanda->license->type, array( 'free' ) ) ) {

                    $this->menuTitle = __('Opt-In Panda', 'optinpanda');
                


                $this->menuIcon = '~/bizpanda/assets/admin/img/menu-icon.png';
            
}
 global $optinpanda;
if ( !in_array( $optinpanda->license->type, array( 'free' ) ) ) {

                $this->menuPostType = OPANDA_POST_TYPE;
            
}

        

    }
}

FactoryPages321::register($optinpanda, 'OPanda_LicenseManagerPage');
 