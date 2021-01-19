<?= $this->getCSS(); ?>
<?php if(isset($this->view) && $this->view == 'view'): ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Comments on Document #<?= $this->document_id; ?></h3>
            </div>
            <!-- ./card-header -->
            <div class="card-body table-responsive p-0" style="height: 320px;">
                <table class="table table-bordered table-hover table-head-fixed">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Title</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($this->results)): ?>
                            <?php foreach ($this->results as $key => $comment): ?>
                            <tr data-widget="expandable-table" aria-expanded="false" class="bg-<?= $comment->status; ?>">
                                <td><?= $comment->id; ?></td>
                                <td><?= $comment->name; ?></td>
                                <td><?= $comment->submitted_date; ?></td>
                                <td><?= $comment->status; ?></td>
                                <td><?= (empty($comment->message)) ? "<em>No comment</em>" : $comment->title; ?></td>
                            </tr>
                            <tr class="expandable-body d-none">
                                <td colspan="5">
                                <p style="display: none;">
                                <strong>Message: </strong> <?= (empty($comment->message)) ? "<em>No message</em>" : $comment->message; ?>
                                </p>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5"><center>No comment yet.</center></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer"></div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-default" id="go_back">Go Back</button>
            </div>
    </div>
</div>
<?php else: ?>
<div id="document-comment" style="display: block;">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Leave a Comment</h3>

            <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body">
            <form id="comment" action="<?= $this->makeUrl("document/putDocumentComment"); ?>" method="post">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control">
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Template</label>
                    <select name="status" class="form-control custom-select">
                        <option selected disabled>Select one</option>
                        <?php $status = array(
                            'working' => 'Working on it',
                            'wrong' => 'Wrong document uploaded',
                            'forreview' => 'For Review'
                        ); ?>
                        <?php foreach( $status as $key => $value ): ?>
                        <option value="<?php echo $key ?>"<?php if( $key == $this->document_status ): ?> selected="selected"<?php endif; ?>><?php echo $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-control custom-select" disabled>
                        <option selected disabled>Select one</option>
                        <?php $status = array(
                            'approved' => 'Approved',
                            'pending' => 'Pending',
                            'forreview' => 'For Review'
                        ); ?>
                        <?php foreach( $status as $key => $value ): ?>
                        <option value="<?php echo $key ?>"<?php if( $key == $this->document_status ): ?> selected="selected"<?php endif; ?>><?php echo $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" id="user_id" name="user_id" class="form-control" value="<?= $this->user_id; ?>">
                    <input type="hidden" id="document_id" name="document_id" class="form-control" value="<?= $this->document_id; ?>">
                </div>
            </form>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-default" id="go_back">Go Back</button>
                <button type="button" class="btn btn-primary" id="comment_submit">Save changes</button>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer"></div>
        <!-- /.card-footer -->
    </div>
    <!-- /.card -->
</div>
<?php endif; ?>
<?= $this->getJS(); ?>