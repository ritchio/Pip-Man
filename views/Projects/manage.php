

<table width="100%" class="table-bordered">
    <tr>
        <th>Name</th>
        <th>Actions</th>
</tr>
<?php
if($data != null){
    
    foreach($data as $project){
        echo '<tr>';
        echo '<td>'.$project["name"].'</td>';
        echo '<td class="text-center"><a href="projects/edit/'.$project["id"].'" target="projects_content" class="button">Edit</a> ';
        echo '<a href="projects/details/'.$project["id"].'" target="projects_content" class="button">Details</a> ';
        echo '<a href="projects/delete/'.$project["id"].'" target="content" class="button">Delete</a></td>';
        echo '</tr>';
    }
}
?>
</table>
