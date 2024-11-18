<?
// Get Parent by ID
function getParentById($parent_id, $conn){
    $sql = "SELECT * FROM parents WHERE parent_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$parent_id]);

    if ($stmt->rowCount() == 1) {
        $parent = $stmt->fetch();
        return $parent;
    } else {
        return 0;  // No parent found
    }
}
