@php
    $headers = $this->headers;
    $rows = $this->previewRows;
    $total = $this->totalRows;
@endphp

<div class="space-y-3">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white">
            Preview Data
        </h3>
        <span class="text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ count($rows) }} dari {{ $total }} baris
        </span>
    </div>

    @if(empty($rows))
        <div
            class="p-4 text-center text-sm text-gray-500 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            Belum ada data untuk ditampilkan. Silakan scan file terlebih dahulu.
        </div>
    @else
        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
            <table
                class="w-full text-sm text-left text-gray-500 dark:text-gray-400 divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap w-10">#</th>
                        @foreach($headers as $header)
                            <th class="px-4 py-3 whitespace-nowrap font-semibold border-l border-gray-200 dark:border-gray-700">
                                {{ str($header)->title()->replace('_', ' ') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                    @foreach($rows as $index => $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-4 py-2 font-mono text-xs text-gray-400">
                                {{ $index + 1 }}
                            </td>
                            @foreach($headers as $header)
                                <td class="px-4 py-2 whitespace-nowrap border-l border-gray-200 dark:border-gray-700">
                                    {{ $row[$header] ?? '-' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($total > count($rows))
            <p class="text-xs text-center text-gray-400 mt-2">
                ... dan {{ $total - count($rows) }} baris lainnya tidak ditampilkan di preview.
            </p>
        @endif
    @endif
</div>