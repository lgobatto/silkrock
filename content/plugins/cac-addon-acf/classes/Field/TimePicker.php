<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACA_ACF_Field_TimePicker extends ACA_ACF_Field {

	public function editing() {
		return new ACA_ACF_Editing_Text( $this->column );
	}

	public function sorting() {
		return new ACA_ACF_Sorting( $this->column );
	}

	public function get_dependent_settings() {
		return array(
			new ACA_ACF_Setting_Time( $this->column ),
		);
	}
}
