<script type="text/javascript">
    function cancelProfil()
    {
        //Vide les champs profil
        $('#tbl_licence_email').val('');
        $('#tbl_licence_last_name').val('');
        $('#tbl_licence_first_name').val('');
        $('#tbl_licence_birthday_day').val('');
        $('#tbl_licence_birthday_month').val('');
        $('#tbl_licence_birthday_year').val('');

        //Vide les champs address
        $('#tbl_licence_id_codepostaux').val('');
        $('#autocomplete_tbl_licence_id_codepostaux').val('');
        $('#tbl_licence_address1').val('');
        $('#tbl_licence_address2').val('');
        $('#tbl_licence_tel').val('');
        $('#tbl_licence_gsm').val('');
        $('#tbl_licence_fax').val('');

        $('#tbl_licence_email').attr('readonly', false);
        $('#tbl_licence_last_name').attr('readonly', false);
        $('#tbl_licence_first_name').attr('readonly', false);
        $('#tbl_licence_birthday_day').attr('readonly', false);
        $('#tbl_licence_birthday_month').attr('readonly', false);
        $('#tbl_licence_birthday_year').attr('readonly', false);
        $('#autocomplete_tbl_licence_id_codepostaux').attr('readonly', false);
        $('#tbl_licence_address1').attr('readonly', false);
        $('#tbl_licence_address2').attr('readonly', false);
        $('#tbl_licence_tel').attr('readonly', false);
        $('#tbl_licence_gsm').attr('readonly', false);
        $('#tbl_licence_fax').attr('readonly', false);

        $('#tbl_licence_id_profil').val('');
        $('#tbl_licence_is_checked').val('0');
        $('#autocomplete_tbl_licence_id_profil').val('');

        return false;
    }
    function validProfil()
    {
        if ($('#tbl_licence_id_profil').val() != '') {
            nIdProfil = $('#tbl_licence_id_profil').val();
            //Ajax on rempli les champs
            $.ajax({
                type: "POST",
                url: "<?php echo url_for('@check_profil') ?>",
                dataType: "json",
                async: false,
                data: "nIdProfil="+nIdProfil,
                success: function(sData) {
                  if (sData.error) {
                    alert(sData.error);
                  } else {
                    profil = sData.profil;
                    $('#tbl_licence_email').val(profil.email);
                    $('#tbl_licence_last_name').val(profil.last_name);
                    $('#tbl_licence_first_name').val(profil.first_name);
                    $('#tbl_licence_birthday_day').val(profil.birthday_day);
                    $('#tbl_licence_birthday_month').val(profil.birthday_month);
                    $('#tbl_licence_birthday_year').val(profil.birthday_year);

                    $('#tbl_licence_id_codepostaux').val(profil.id_codepostaux);
                    $('#autocomplete_tbl_licence_id_codepostaux').val(profil.ville);
                    $('#tbl_licence_address1').val(profil.address1);
                    $('#tbl_licence_address2').val(profil.address2);
                    $('#tbl_licence_tel').val(profil.tel);
                    $('#tbl_licence_gsm').val(profil.gsm);
                    $('#tbl_licence_fax').val(profil.fax);
                    $('#tbl_licence_is_checked').val('1');

                    $('#tbl_licence_email').attr('readonly', true);
                    $('#tbl_licence_last_name').attr('readonly', true);
                    $('#tbl_licence_first_name').attr('readonly', true);
                    $('#tbl_licence_birthday_day').attr('readonly', true);
                    $('#tbl_licence_birthday_month').attr('readonly', true);
                    $('#tbl_licence_birthday_year').attr('readonly', true);

                    $('#autocomplete_tbl_licence_id_codepostaux').attr('readonly', true);
                    $('#tbl_licence_address1').attr('readonly', true);
                    $('#tbl_licence_address2').attr('readonly', true);
                    $('#tbl_licence_tel').attr('readonly', true);
                    $('#tbl_licence_gsm').attr('readonly', true);
                    $('#tbl_licence_fax').attr('readonly', true);
                  }
                },
                error: function (sData) {
                   alert('Error server');
                }
            });
        }

    }
</script>
<div>
    <div class="sb_bouton_a">
        <a href="#" onClick="cancelProfil()">annuler</a>
        <a href="#" onClick="validProfil()">Valider</a>
    </div>
</div>