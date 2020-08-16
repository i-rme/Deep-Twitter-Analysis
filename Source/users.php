<?php
require 'includes/header.php';
$users  = $twitterReader->getUsers();


echo '
<style>
th,td
{
    overflow: hidden;
}
</style>
';


echo '
    <div class="tree-nav-content">
        
    <h3>Users</h3>
    <div class="divider"></div>
    <p>Showing rows of users.</p>
';

// Print Users in a nice table
echo '<table class="table small striped">';
echo '
    <thead><tr>
    <th>Id</th>
    <th>Name</th>
    <th>S. Name</th>
    <th>Location</th>
    <th>Description</th>
    </tr></thead>

    <tbody style="font-size: 9pt;">';
foreach ($users as $user)
{
    echo $user->toRow();
}

echo '
    </tbody>
    </table>';


echo '
                </div>
';
       
require 'includes/footer.php';