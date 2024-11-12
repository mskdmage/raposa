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

<div class="columns is-centered">
    <section class="section">
        <div class="container">
            <div class="card has-background-primary has-text-centered mb-5">
                <div class="card-content">
                    <figure class="image is-inline-block">
                        <img src="<?= "$web_root/assets/images/possum.png"; ?>" alt="Possum">
                    </figure>
                    <h1 class="title mt-4">Hosts</h1>
                </div>
            </div>

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
                            <a class="button is-info is-small is-light is-outlined" href="/machine?machine=<?= htmlspecialchars($machine['name']); ?>">
                                <span class="icon"><i class="fas fa-cogs"></i></span>
                                <span>Control</span>
                            </a>
                        </td>
                        <td>
                            <a class="button is-danger is-small is-light is-outlined" href="/index.php?id=<?= htmlspecialchars($machine['id']) ?>" onclick="return confirm('Are you sure you want to delete this machine?');">
                                <span class="icon"><i class="fas fa-trash"></i></span>
                                <span>Delete</span>
                            </a>
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