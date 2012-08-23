<script type="text/javascript">
    $(document).ready(function() {

        if ($('#tbl_licence_is_medical').attr("checked") == undefined)
        {
            $('.sf_admin_form_field_date_medical').hide();
        }
        $('#tbl_licence_is_medical').click(function () {
            if ($('#tbl_licence_is_medical').attr("checked") == undefined)
            {
                $('.sf_admin_form_field_date_medical').hide();
                $('#tbl_licence_date_medical_day').val('');
                $('#tbl_licence_date_medical_month').val('');
                $('#tbl_licence_date_medical_year').val('');
            } else {
                alert('J\'atteste sur l\'honneurde la possession et de la validité du certificat de ce licencié sous peine des poursuites.');
                $('.sf_admin_form_field_date_medical').show();
            }
        });
    });
</script>