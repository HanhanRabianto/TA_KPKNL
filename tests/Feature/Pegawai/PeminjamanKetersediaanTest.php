<?php

namespace Tests\Feature\Pegawai;

use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\Pengemudi;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PeminjamanKetersediaanTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('peminjaman');
        Schema::dropIfExists('pengemudi');
        Schema::dropIfExists('kendaraan');
        Schema::dropIfExists('pengguna');

        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('nama_seksi');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('peran')->default('pegawai');
            $table->boolean('aktif')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('plat')->unique();
            $table->string('jenis');
            $table->string('tipe')->default('Minibus');
            $table->unsignedTinyInteger('kapasitas')->default(7);
            $table->string('status')->default('Tersedia');
            $table->timestamps();
        });

        Schema::create('pengemudi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('status')->default('Tersedia');
            $table->timestamps();
        });

        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengguna_id');
            $table->unsignedBigInteger('kendaraan_id')->nullable();
            $table->unsignedBigInteger('pengemudi_id')->nullable();
            $table->string('lokasi_tujuan');
            $table->boolean('menginap')->default(false);
            $table->string('tujuan');
            $table->string('petugas');
            $table->unsignedTinyInteger('penumpang')->default(1);
            $table->text('catatan')->nullable();
            $table->dateTime('mulai');
            $table->dateTime('selesai')->nullable();
            $table->string('status')->default('menunggu');
            $table->string('nomor_surat')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamp('diajukan_pada')->useCurrent();
            $table->dateTime('diputuskan_pada')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('peminjaman');
        Schema::dropIfExists('pengemudi');
        Schema::dropIfExists('kendaraan');
        Schema::dropIfExists('pengguna');

        parent::tearDown();
    }

    public function test_other_sections_cannot_see_resources_reserved_by_another_section(): void
    {
        $section = $this->createSection('Seksi Operasional', 'operasional');
        $requester = $this->createSection('Seksi Operasional', 'operasional-pemohon');
        $otherSection = $this->createSection('Seksi Pelayanan', 'pelayanan');

        $reservedVehicle = $this->createVehicle('D 1001 KN');
        $sameSectionVehicle = $this->createVehicle('D 1002 KN');
        $rejectedVehicle = $this->createVehicle('D 1003 KN');
        $reservedDriver = $this->createDriver('Pengemudi Terpesan');
        $sameSectionDriver = $this->createDriver('Pengemudi Seksi Sama');
        $rejectedDriver = $this->createDriver('Pengemudi Bebas');

        $this->createLoan($section, $sameSectionVehicle, $sameSectionDriver);
        $this->createLoan($otherSection, $reservedVehicle, $reservedDriver);
        $this->createLoan($otherSection, $rejectedVehicle, $rejectedDriver, 'ditolak');

        $response = $this->actingAs($requester)->get(route('pegawai.peminjaman.create'));

        $response->assertOk()
            ->assertSee($sameSectionVehicle->plat)
            ->assertSee($rejectedVehicle->plat)
            ->assertDontSee($reservedVehicle->plat)
            ->assertSee($sameSectionDriver->nama)
            ->assertSee($rejectedDriver->nama)
            ->assertDontSee($reservedDriver->nama);
    }

    public function test_section_cannot_submit_a_vehicle_reserved_by_another_section(): void
    {
        $owner = $this->createSection('Seksi Operasional', 'operasional');
        $requester = $this->createSection('Seksi Pelayanan', 'pelayanan');
        $reservedVehicle = $this->createVehicle('D 1001 KN');
        $reservedDriver = $this->createDriver('Pengemudi Terpesan');
        $availableDriver = $this->createDriver('Pengemudi Tersedia');
        $this->createLoan($owner, $reservedVehicle, $reservedDriver);

        $response = $this->actingAs($requester)->post(route('pegawai.peminjaman.store'), $this->validLoanDetails(
            $reservedVehicle->id,
            $availableDriver->id,
        ));

        $response->assertRedirect(route('pegawai.peminjaman.create'))
            ->assertSessionHas('err', 'Kendaraan ini sudah diajukan oleh seksi lain. Silakan pilih kendaraan lain.');
        $this->assertDatabaseCount('peminjaman', 1);
    }

    public function test_section_cannot_submit_a_driver_reserved_by_another_section(): void
    {
        $owner = $this->createSection('Seksi Operasional', 'operasional');
        $requester = $this->createSection('Seksi Pelayanan', 'pelayanan');
        $reservedVehicle = $this->createVehicle('D 1001 KN');
        $anotherVehicle = $this->createVehicle('D 1002 KN');
        $reservedDriver = $this->createDriver('Pengemudi Terpesan');
        $this->createLoan($owner, $reservedVehicle, $reservedDriver);

        $response = $this->actingAs($requester)->post(route('pegawai.peminjaman.store'), $this->validLoanDetails(
            $anotherVehicle->id,
            $reservedDriver->id,
        ));

        $response->assertRedirect(route('pegawai.peminjaman.create'))
            ->assertSessionHas('err', 'Pengemudi ini sudah diajukan oleh seksi lain. Silakan pilih pengemudi lain.');
        $this->assertDatabaseCount('peminjaman', 1);
    }

    private function createSection(string $namaSeksi, string $username): User
    {
        return User::create([
            'nama_seksi' => $namaSeksi,
            'username' => $username,
            'password' => 'password',
            'peran' => 'pegawai',
            'aktif' => true,
        ]);
    }

    private function createVehicle(string $plat): Kendaraan
    {
        return Kendaraan::create([
            'plat' => $plat,
            'jenis' => 'Toyota Avanza',
            'tipe' => 'Minibus',
            'kapasitas' => 7,
            'status' => 'Tersedia',
        ]);
    }

    private function createDriver(string $nama): Pengemudi
    {
        return Pengemudi::create([
            'nama' => $nama,
            'status' => 'Tersedia',
        ]);
    }

    private function createLoan(
        User $section,
        Kendaraan $vehicle,
        Pengemudi $driver,
        string $status = 'menunggu',
    ): Peminjaman {
        return Peminjaman::create([
            'pengguna_id' => $section->id,
            'kendaraan_id' => $vehicle->id,
            'pengemudi_id' => $driver->id,
            'lokasi_tujuan' => 'Dalam kota',
            'menginap' => false,
            'tujuan' => 'Kunjungan dinas',
            'petugas' => 'Pegawai',
            'penumpang' => 2,
            'mulai' => now()->addDay(),
            'status' => $status,
            'alasan_tolak' => $status === 'ditolak' ? 'Jadwal tidak tersedia.' : null,
        ]);
    }

    private function validLoanDetails(int $vehicleId, int $driverId): array
    {
        return [
            'kendaraan_id' => $vehicleId,
            'pengemudi_id' => $driverId,
            'lokasi_tujuan' => 'Dalam kota',
            'menginap' => '0',
            'tujuan' => 'Kunjungan dinas',
            'petugas' => 'Pegawai',
            'penumpang' => 2,
            'tanggal_mulai' => today()->addDay()->toDateString(),
            'jam_mulai' => '08:00',
        ];
    }
}
