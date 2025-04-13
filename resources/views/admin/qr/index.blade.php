@php use Illuminate\Support\Facades\Crypt; @endphp
@extends('layouts.admin')

@section('admin')
    @if(session('success'))
        @component('components.alert', ['variant' => 'success'])
            {{ __(session('success')) }}
        @endcomponent
    @endif
    <x-heading1>
        QR's
    </x-heading1>
    <x-table-default>
        <thead class="bg-accent-content">
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Archivos</th>
            <th>Imagen</th>
        </tr>
        </thead>
        <tbody>
        @forelse($qrs as $qr)
            <tr>
                <td>{{ $qr->id }}</td>
                <td>{{ $qr->client->legal_name }}</td>
                <td>{{ $qr->product->name }}</td>
                <td>{{ $qr->product->files->count() }}</td>
                <td>
                    <div class="tooltip" data-tip="zoom">
                        <button class="relative cursor-pointer group" onclick="my_modal_{{ $qr->id }}.showModal()">
                        <span class="absolute inset-0 flex items-center justify-center w-100 h-100 max-w-full max-h-full p-5 opacity-0 group-hover:opacity-100 transition duration-300 ease-in-out bg-black/50">
                            <x-zondicon-search />
                        </span>
                            <img src="{{ asset(Crypt::decrypt($qr->url_qrcode)) }}"
                                 alt="Código QR de {{ $qr->product->name }}">
                        </button>
                    </div>
                    <dialog id="my_modal_{{ $qr->id }}" class="modal">
                        <div class="modal-box">
                            <div class="printer">
                                <div class="bg-white p-8 rounded-[2rem] shadow-lg"
                                     style="border-radius: 2rem; border: 2px solid #e5e7eb;">
                                    <img class="w-100 mx-auto" src="{{ asset(Crypt::decrypt($qr->url_qrcode)) }}"
                                         alt="Código QR de {{ $qr->product->name }}">
                                    <div class="flex justify-center items-center gap-5 mt-5">
                                        <h2 class="text-6xl montserrat" style="color: #000000 !important;">AR</h2>
                                        <div class="ticks">
                                            <img src="{{ asset('assets/ok.svg') }}" alt="tick svg">
                                            <img src="{{ asset('assets/ok.svg') }}" alt="tick svg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button onclick="downloadQrPdf({{ $qr->id }}, '{{ addslashes($qr->product->name) }}')"
                                    type="button" class="btn btn-primary mx-auto w-fit block mt-10">
                                Descargar imprimible
                            </button>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center text-2xl bg-content-200 py-2.5" colspan="100%">No hay QR's generados</td>
            </tr>
        @endforelse
        </tbody>
    </x-table-default>

    {{ $qrs->appends(request()->query())->links() }}
@endsection
@push('scripts')
    <script>
        window.downloadQrPdf = function(qrId, productName, size = 'small') {
            const element = document.querySelector(`#my_modal_${qrId} .printer`);
            const arElement = element.querySelector('.montserrat');
            const originalColor = window.getComputedStyle(arElement).color;
            arElement.style.color = '#000000';

            // Definir dimensiones según el tamaño seleccionado
            let pdfWidth, pdfHeight;
            if (size === 'small') {
                pdfWidth = 105; // A6 width en mm
                pdfHeight = 148; // A6 height en mm
            } else {
                pdfWidth = 210; // A4 width en mm
                pdfHeight = 297; // A4 height en mm
            }

            const pdf = new jsPDF('p', 'mm', [pdfWidth, pdfHeight]);

            // Opciones modificadas para html2canvas
            html2canvas(element, {
                scale: 2,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null,
                logging: true,
                removeContainer: true
            }).then(canvas => {
                try {
                    // Calcular dimensiones manteniendo proporción
                    const imgWidth = pdfWidth - 20;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    const yPosition = (pdfHeight - imgHeight) / 2;

                    // Añadir la imagen al PDF
                    const imgData = canvas.toDataURL('image/png');
                    pdf.setFillColor(255, 255, 255, 0); // Fondo transparente
                    pdf.addImage(imgData, 'PNG', 10, yPosition, imgWidth, imgHeight);

                    // Descargar el PDF
                    pdf.save(`qr-${productName.replace(/\s+/g, '-')}-${size}.pdf`);
                    arElement.style.color = originalColor;
                } catch (error) {
                    console.error('Error al generar el PDF:', error);
                    alert('Hubo un problema al generar el PDF. Por favor, inténtelo de nuevo.');
                }
            }).catch(error => {
                console.error('Error en html2canvas:', error);
                arElement.style.color = originalColor;
                alert('No se pudo generar la imagen. Por favor, inténtelo de nuevo.');
            });
        };
    </script>
@endpush