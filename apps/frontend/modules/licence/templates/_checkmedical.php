<script type="text/javascript">
    $(document).ready(function() {

        if ($('#tbl_licence_is_medical').attr("checked") == undefined)
        {
            $('.sf_admin_form_field_date_medical').hide();
            $('.sf_admin_form_field_lastname_doctor').hide();
            $('.sf_admin_form_field_firstname_doctor').hide();
            $('.sf_admin_form_field_rpps').hide();
        }
        $('#tbl_licence_is_medical').click(function () {
            if ($('#tbl_licence_is_medical').attr("checked") == undefined)
            {
                $('.sf_admin_form_field_date_medical').hide();
                $('.sf_admin_form_field_lastname_doctor').hide();
                $('.sf_admin_form_field_firstname_doctor').hide();
                $('.sf_admin_form_field_rpps').hide();
                $('#tbl_licence_lastname_doctor').val('');
                $('#tbl_licence_firstname_doctor').val('');
                $('#tbl_licence_rpps').val('');
                $('#tbl_licence_date_medical_day').val('');
                $('#tbl_licence_date_medical_month').val('');
                $('#tbl_licence_date_medical_year').val('');
            } else {
                alert('J\'atteste sur l\'honneur du contrôle et de la validité du certificat médical. En cas de fausse déclaration, je suis informé que j\'engagerai ma responsabilité civile et/ou pénale et m\'exposerai aux poursuites prévues par la Loi.');
                $('.sf_admin_form_field_date_medical').show();
                $('.sf_admin_form_field_lastname_doctor').show();
                $('.sf_admin_form_field_firstname_doctor').show();
                $('.sf_admin_form_field_rpps').show();
            }
        });
    });
</script>