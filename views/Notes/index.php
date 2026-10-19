<h2>Notes</h2>
<div class="card">
    <div class="card-body">
        <form action="notes/create" method="post" target="notes_content">
            <div class="input-group">
                <span class="input-group-text">Note</span>
                <textarea name="description"></textarea>
            </div>
            <input type="submit" value="Save note" class="button">
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <select id="noteActive" onchange="getContent({url:'notes/index/'+this.value,target:'notes_content'})">
            <option value="1" <?php echo ($data["selected"]==1?"selected":"")?>>Only active</option>
            <option value="0" <?php echo ($data["selected"]==0?"selected":"")?>>All</option>
</select>
    </div>
</div>
<div id="note_list">
<?php 
if($data["notes"] != null){
    foreach($data["notes"] as $note){
        echo '<div class="card">
        <div class="card-tool">
            <a href="notes/close/'.$note['id'].'" target="notes_content" alt="Close note">X</a>
        </div>
        <div class="card-body overflow-wrap">'.nl2br($note["description"]).'</div>
        </div>';       
    }
}
?>
</div>
