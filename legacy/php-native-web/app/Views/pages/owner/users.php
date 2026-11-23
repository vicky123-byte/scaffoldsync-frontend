<section class="panel">
    <div class="panel-head">
        <div>
            <h2>Daftar Pengguna</h2>
            <p>Pantau akun Admin, Owner, dan Mandor yang terdaftar dalam sistem.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($store['users'] as $u): ?>
                    <tr>
                        <td><b><?= h($u['nama']) ?></b></td>
                        <td><?= h($u['email']) ?></td>
                        <td><?= h($u['role']) ?></td>
                        <td><?= status_badge($u['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>