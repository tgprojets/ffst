<script type="text/javascript">
    $(document).ready(function() {
        console.log('sqd');
        if ($('#tbl_licence_is_foreign').attr("checked") == undefined)
        {
            $('.sf_admin_form_field_country').hide();
            $('.sf_admin_form_field_city_foreign').hide();
            $('.sf_admin_form_field_cp_foreign').hide();
        }
        $('#tbl_licence_is_foreign').click(function () {
            if ($('#tbl_licence_is_foreign').attr("checked") == undefined)
            {
                $('.sf_admin_form_field_country').hide();
                $('.sf_admin_form_field_city_foreign').hide();
                $('.sf_admin_form_field_cp_foreign').hide();
                $('.sf_admin_form_field_id_codepostaux').show();
            } else {
                $('.sf_admin_form_field_country').show();
                $('.sf_admin_form_field_city_foreign').show();
                $('.sf_admin_form_field_cp_foreign').show();
                $('.sf_admin_form_field_id_codepostaux').hide();
            }
        });
    });
</script>