<?php
/**
 * Mantis BT 의 사용자 정의 필드 Validate 체크용 플러그인
 * Mantis BT의 커스텀필드는 frontEnd단에서 maxlength 는 되나 minlength 는 안되고 있음.
 * submit 후에 api 응답에서나 알 수 있는데, frontend 단에서 할 수있도록 변경함
 * 
 * @author loudon23
 */
class CfDefValidatePlugin extends MantisPlugin {
    function register()
    {
        $this->name = 'CustomCfDef';
        $this->description = '사용자 지정필드의 minlength값 유효성 확인 플러그인 ';
        $this->page = '';
        $this->version = '1.0.0';
        $this->requires = array(
            "MantisCore" => "2.0.0",
        );
        $this->author = 'loudon23';
        $this->contact = 'loudon23@naver.com';
        $this->url = 'github.com/loudon23';
    }

    function config() {
        global $g_custom_field_type_definition;

        // 커스텀필드의 출력 함수들 재 정의
        $g_custom_field_type_definition[CUSTOM_FIELD_TYPE_STRING]['#function_print_input'] = 'CfDefValidatePlugin::cfdef_input_textbox';
        $g_custom_field_type_definition[CUSTOM_FIELD_TYPE_NUMERIC]['#function_print_input'] = 'CfDefValidatePlugin::cfdef_input_textbox';
        $g_custom_field_type_definition[CUSTOM_FIELD_TYPE_FLOAT]['#function_print_input'] = 'CfDefValidatePlugin::cfdef_input_textbox';
        $g_custom_field_type_definition[CUSTOM_FIELD_TYPE_EMAIL]['#function_print_input'] = 'CfDefValidatePlugin::cfdef_input_textbox';

        return array();
    }

    function cfdef_input_textbox( array $p_field_def, $p_custom_field_value, $p_required = '' ) {
        echo '<input ' . helper_get_tab_index() . ' type="text" id="custom_field_' . $p_field_def['id'] . '" name="custom_field_' . $p_field_def['id'] . '" size="80"' . $p_required;
        if( 0 < $p_field_def['length_max'] ) {
            echo ' maxlength="' . $p_field_def['length_max'] . '"';
        } else {
            echo ' maxlength="255"';
        }

        // 필드의 최소값이 있을 경우 frontend에서 검증하도록 변경
        if( 0 < $p_field_def['length_min'] ){
            echo ' minlength="' . $p_field_def['length_min'] . '" ';
        }

        echo ' value="' . string_attribute( $p_custom_field_value ) .'" />';
    }
}