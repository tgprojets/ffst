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
                alert('J\'atteste sur l\'honneur du contrôle et de la validité du certificat médical. En cas de fausse déclaration, je suis informé que j\'engagerai ma responsabilité civile et/ou pénale et m\'exposerai aux poursuites prévues par la Loi.');
                $('.sf_admin_form_field_date_medical').show();
            }
        });
    });
</script>