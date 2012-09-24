<script type="text/javascript">
    $(document).ready(function() {
        $('#generate_password').click(function() {
            if ($('#tbl_club_password').size()) {
                $.ajax({
                    type: "POST",
                    url:"<?php echo url_for('@generate_password') ?>",
                    dataType: "json",
                    async: false,
                    success: function(sData) {
                        if (sData.password != undefined) {
                            $('#tbl_club_password').val(sData.password);
                        } else {
                            alert('Error server');
                        }
                    },
                    error: function (sData) {
                       alert('Error server');
                    }
                });
            }
            return false;
        });
    });
</script>
<div class="sf_admin_form_row">
    <div class="sb_bouton_a">
        <a href="#" id="generate_password">Génerer un mot de passe</a>
    </div>
</div>
