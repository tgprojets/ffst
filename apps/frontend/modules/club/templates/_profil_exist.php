<script type="text/javascript">

    function cancelProfil()
    {
        $.ajax({
            type: "POST",
            url: "<?php echo url_for('@check_user_cancel_club') ?>",
            dataType: "json",
            async: false,
            data: "nIdClub="+$('#tbl_club_id').val(),
            success: function(sData) {
              if (sData.error) {
                alert(sData.error);
              } else {
                profil = sData.profil;
                $('#tbl_club_email').val(profil.email);
                $('#tbl_club_username').val(profil.username);
                $('#tbl_club_nom').val(profil.last_name);
                $('#tbl_club_prenom').val(profil.first_name);
                if (profil.id == '') {
                    $('#tbl_club_id_user').val('');
                    $('#autocomplete_tbl_club_id_user').val('');
                } else {
                    $('#tbl_club_id_user').val(profil.id);
                    $('#autocomplete_tbl_club_id_user').val(profil.last_name+' '+profil.first_name+'('+profil.username+')');
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
        if ($('#tbl_club_id_user').val() != '') {
            nIdProfil = $('#tbl_club_id_user').val();
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
                    $('#tbl_club_email').val(profil.email);
                    $('#tbl_club_username').val(profil.username);
                    $('#tbl_club_nom').val(profil.last_name);
                    $('#tbl_club_prenom').val(profil.first_name);
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
        <a href="#tbl_club_id_user" onClick="cancelProfil()">Annuler</a>
        <a href="#tbl_club_id_user" onClick="validProfil()">Valider</a>
    </div>
</div>