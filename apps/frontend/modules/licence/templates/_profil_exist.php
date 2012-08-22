<script type="text/javascript">
    $(document).ready(function() {
        $('form').submit(function() {
            enabledForm();
        });
        if ($('#tbl_licence_is_checked').val() == 1) {
            disabledForm();
        }
    });
    function disabledForm()
    {
        $('#tbl_licence_email').attr('disabled', true);
        $('#tbl_licence_last_name').attr('disabled', true);
        $('#tbl_licence_first_name').attr('disabled', true);
        $('#tbl_licence_birthday_day').attr('disabled', true);
        $('#tbl_licence_birthday_month').attr('disabled', true);
        $('#tbl_licence_birthday_year').attr('disabled', true);

        $('#autocomplete_tbl_licence_id_codepostaux').attr('disabled', true);
        $('#tbl_licence_address1').attr('disabled', true);
        $('#tbl_licence_address2').attr('disabled', true);
        $('#tbl_licence_tel').attr('disabled', true);
        $('#tbl_licence_gsm').attr('disabled', true);
        $('#tbl_licence_fax').attr('disabled', true);
    }
    function enabledForm()
    {
        $('#tbl_licence_email').attr('disabled', false);
        $('#tbl_licence_last_name').attr('disabled', false);
        $('#tbl_licence_first_name').attr('disabled', false);
        $('#tbl_licence_birthday_day').attr('disabled', false);
        $('#tbl_licence_birthday_month').attr('disabled', false);
        $('#tbl_licence_birthday_year').attr('disabled', false);
        $('#autocomplete_tbl_licence_id_codepostaux').attr('disabled', false);
        $('#tbl_licence_address1').attr('disabled', false);
        $('#tbl_licence_address2').attr('disabled', false);
        $('#tbl_licence_tel').attr('disabled', false);
        $('#tbl_licence_gsm').attr('disabled', false);
        $('#tbl_licence_fax').attr('disabled', false);
    }
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
        $('#tbl_licence_international').attr('checked', false);
        $('#tbl_licence_race_nordique').attr('checked', false);
        $('#tbl_licence_is_familly').attr('checked', false);
        $('#tbl_licence_cnil').attr('checked', false);

        $('#tbl_licence_id_profil').val('');
        $('#tbl_licence_is_checked').val('0');
        $('#autocomplete_tbl_licence_id_profil').val('');
        enabledForm();

        return false;
    }

    function validProfil()
    {
        if ($('#tbl_licence_id_profil').val() != '') {
            nIdProfil = $('#tbl_licence_id_profil').val();
            nIdClub   = $('#tbl_licence_id_club').val();
            //Ajax on rempli les champs
            $.ajax({
                type: "POST",
                url: "<?php echo url_for('@check_profil') ?>",
                dataType: "json",
                async: false,
                data: "nIdProfil="+nIdProfil+"&nIdClub="+nIdClub,
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
                    $('#tbl_licence_id_category').val(profil.id_category);
                    $('#tbl_licence_id_typelicence').val(profil.id_typelicence);
                    if (profil.international == 1) { $('#tbl_licence_international').attr('checked', true); }
                    if (profil.race_nordique == 1) { $('#tbl_licence_race_nordique').attr('checked', true); }
                    if (profil.is_familly == 1) { $('#tbl_licence_is_familly').attr('checked', true); }
                    if (profil.cnil == 1) { $('#tbl_licence_cnil').attr('checked', true); }
                    $('#tbl_licence_is_checked').val('1');
                    disabledForm();

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