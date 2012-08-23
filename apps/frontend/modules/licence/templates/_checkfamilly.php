<script type="text/javascript">
    $(document).ready(function() {

        if ($('#tbl_licence_is_familly').attr("checked") == undefined)
        {
            $('.sf_admin_form_field_id_familly').hide();
        }
        $('#tbl_licence_is_familly').click(function () {
            if ($('#tbl_licence_is_familly').attr("checked") == undefined)
            {
                $('.sf_admin_form_field_id_familly').hide();
                $('#tbl_licence_id_familly').val('');
                $('#autocomplete_tbl_licence_id_familly').val('');
            } else {
                $('.sf_admin_form_field_id_familly').show();
            }
        });
    });
</script>
