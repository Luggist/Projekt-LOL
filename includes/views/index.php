<?php

echo $this->header;

?>
<div id="main">
    <div class="row">

        <button class="btn btn-primary" data-toggle="modal" data-target="#editModal"><i class="glyphicon glyphicon-plus"></i> Neue Adresse anlegen</button>

        <?php if($this->addresses): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Vorname</th>
                    <th>Ort</th>
                    <th>Bearbeiten</th>
                    <th>Löschen</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($this->addresses as $address): ?>
                <tr>
                    <td><?php echo $address->id; ?></td>
                    <td><?php echo $address->lastname; ?></td>
                    <td><?php echo $address->firstname; ?></td>
                    <td><?php echo $address->city; ?></td>
                    <td><button class="btn btn-default" data-toggle="modal" data-target="#editModal" data-id="<?php echo $address->id; ?>"><i class="glyphicon glyphicon-pencil"></i> Bearbeiten</button></td>
                    <td><a class="btn btn-danger triggerDelete" href="api/address/" data-id="<?php echo $address->id; ?>"><i class="glyphicon glyphicon-trash"></i> Löschen</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
                <p>&nbsp;</p>
                <div class="alert alert-info">Noch keine Adressen vorhanden - Sie können über den Button <strong>Neue Adresse anlegen</strong> eine neue Adresse Ihrer Adressverwaltung hinzufügen.</div>
        <?php endif; ?>

    </div>
</div>



<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                <button type="button" class="btn btn-primary"></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php

echo $this->footer;

?>