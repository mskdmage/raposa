<?php
$conn = connect_to_db();
$sql = "SELECT * FROM machines";
$result = $conn->query($sql);

if ($result) {
    $machines = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error: " . $conn->error;
    $machines = [];
}
?>

<div class="columns is-multiline">
    <section class="section">
        <div class="container">
            <div class="card has-background-dark has-text-centered">
                <figure class="image is-inline-block">
                    <img src="<?= "$web_root/assets/images/possum.png"; ?>" alt="Possum">
                </figure>
            </div>
            <h1 class="title has-text-centered">Hosts</h1>
            <table class="table is-striped is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Last Active</th>
                        <th>Name</th>
                        <th>IP</th>
                        <th>Select</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($machines as $machine): ?>
                    <tr>
                        <td><?= htmlspecialchars($machine['id']) ?></td>
                        <td><?= htmlspecialchars($machine['last_active']) ?></td>
                        <td><?= htmlspecialchars($machine['name']) ?></td>
                        <td><?= htmlspecialchars($machine['ip']) ?></td>
                        <td>
                            <a href="/machine?machine=<?= htmlspecialchars($machine['name']); ?>">Control</a>
                        </td>
                        <td>
                            <a href="/index.php?id=<?= htmlspecialchars($machine['id']) ?>">💣</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php
if (isset($_GET['id'])) {
    $machine_id = $_GET['id'];
    $conn = connect_to_db();
    $query = "DELETE FROM machines WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $machine_id);
    $stmt->execute();
    $conn->close();
    header('Location: /index.php');
    exit();
}
?>