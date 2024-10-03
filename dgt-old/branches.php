<?php $page_title = 'Branches';
include("header.php"); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <?php echo searchInput('a', 'form-control-sm'); ?>
                </div>
                <div class="d-flex">
                    <?php echo addNew('branch-add', '', 'btn-sm'); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive" style="height: 73dvh;">
                        <table class="table mb-0 table-bordered fix-head-table table-sm">
                            <thead>
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>BRANCH CODE</th>
                                <th>NAME</th>
                                <th>FATHER NAME</th>
                                <th>ADDRESS</th>
                                <th>COUNTRY</th>
                                <th>CITY / ZIP CODE</th>
                                <th>Mob. / Ph.</th>
                                <th>WHATSAPP / EMAIL</th>
                                <th>ADMIN LOGIN</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $branches = fetch('branches');
                            $x = 1;
                            while ($branch = mysqli_fetch_assoc($branches)) { ?>
                                <tr class="font-size-12">
                                    <td><?php echo $x; ?></td>
                                    <td>
                                        <?php echo '<a href="branch-add?id=' . $branch['id'] . '">' . $branch['b_code'];
                                        echo '<br>' . $branch['b_name'];
                                        echo '</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $branch['name']; ?></td>
                                    <td><?php echo $branch['father_name']; ?></td>
                                    <td><?php echo $branch['address']; ?></td>
                                    <td><?php echo countryName($branch['country_id']); ?></td>
                                    <td>
                                        <?php echo $branch['city'];
                                        echo '<br>' . $branch['zip_code']; ?>
                                    </td>
                                    <td>
                                        <?php echo href_link2('Mobile', $branch['mobile'], $branch['mobile'], true, 'Mobile');
                                        echo '<br>';
                                        echo href_link2('Phone', $branch['phone'], $branch['phone'], true, 'Phone'); ?>
                                    </td>
                                    <td>
                                        <?php echo href_link2('WhatsApp', $branch['whatsapp'], $branch['whatsapp'], true, 'WhatsApp', '_blank'); ?>
                                        <?php echo href_link2('Email', $branch['email'], $branch['email'], true, 'Email'); ?>
                                    </td>
                                    <?php $branch_user_id = $branch['user_id'];
                                    $users = fetch('users', array('id' => $branch_user_id));
                                    $record_user = mysqli_fetch_assoc($users); ?>
                                    <td>
                                        <?php echo $record_user['username'];
                                        echo '<br>'.$record_user['pass']; ?>
                                    </td>
                                </tr>
                                <?php $x++;
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php"); ?>