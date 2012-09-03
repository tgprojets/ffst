<script type="text/javascript">

    function cancelProfil()
    {
        $.ajax({
            type: "POST",
            url: "<?php echo url_for('@check_user_cancel_ligue') ?>",
            dataType: "json",
            async: false,
            data: "nIdLigue="+$('#tbl_ligue_id').val(),
            success: function(sData) {
              if (sData.error) {
                alert(sData.error);
              } else {
                profil = sData.profil;
                $('#tbl_ligue_email').val(profil.email);
                $('#tbl_ligue_username').val(profil.username);
                $('#tbl_ligue_nom').val(profil.last_name);
                $('#tbl_ligue_prenom').val(profil.first_name);
                if (profil.id == '') {
                    $('#tbl_ligue_id_user').val('');
                    $('#autocomplete_tbl_ligue_id_user').val('');
                } else {
                    $('#tbl_ligue_id_user').val(profil.id);
                    $('#autocomplete_tbl_ligue_id_user').val(profil.last_name+' '+profil.first_name+'('+profil.username+')');
                }
              }
            },
            error: function (sData) {
               alert('Error server');
            }
        });
        return false;
    }

    function validProfil()
    {
        if ($('#tbl_ligue_id_user').val() != '') {
            nIdProfil = $('#tbl_ligue_id_user').val();
            //Ajax on rempli les champs
            $.ajax({
                type: "POST",
                url: "<?php echo url_for('@check_user') ?>",
                dataType: "json",
                async: false,
                data: "nIdProfil="+nIdProfil,
                success: function(sData) {
                  if (sData.error) {
                    alert(sData.error);
                  } else {
                    profil = sData.profil;
                    $('#tbl_ligue_email').val(profil.email);
                    $('#tbl_ligue_username').val(profil.username);
                    $('#tbl_ligue_nom').val(profil.last_name);
                    $('#tbl_ligue_prenom').val(profil.first_name);
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
    <div class="sb_bouton_a button_right">
        <a href="#tbl_ligue_id_user" onClick="cancelProfil()">annuler</a>
        <a href="#tbl_ligue_id_user" onClick="validProfil()">Valider</a>
    </div>
</div>