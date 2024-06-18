<x-modal.base id="modal--customer-calendar" modal-size="modal-lg">
    <x-slot name="title">Kamar - Nama Customer</x-slot>
    <x-slot name="body">
        <table class="table table-striped">
            <tbody>
            <tr>
                <td>Kode Pemesanan</td>
                <td class="field" id="code"></td>
            </tr>
            <tr>
                <td>Nama Customer</td>
                <td class="field" id="name"></td>
            </tr>
            <tr>
                <td>No Telp Customer</td>
                <td class="field" id="phone"></td>
            </tr>
            <tr>
                <td>Email Customer</td>
                <td class="field" id="email"></td>
            </tr>
            <tr>
                <td>Alamat Customer</td>
                <td class="field" id="address"></td>
            </tr>
            <tr>
                <td>Tanggal Pemesanan</td>
                <td class="field" id="date"></td>
            </tr>
            <tr>
                <td>Jumlah Hari</td>
                <td class="field" id="days"></td>
            </tr>
            <tr>
                <td>Kamar</td>
                <td class="field" id="room"></td>
            </tr>
            </tbody>
        </table>
    </x-slot>
    <x-slot:customModalFooter>

    </x-slot:customModalFooter>
</x-modal.base>
