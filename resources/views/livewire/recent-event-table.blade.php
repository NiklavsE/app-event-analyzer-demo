<div class="relative shadow-md sm:rounded-lg space-y-4">
    <div class="px-3 py-1 text-m text-center font-semibold tracking-wider text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 rounded-t-lg">
        Recent Events
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-2">
                    Name
                </th>
                <th scope="col" class="px-6 py-2">
                    Timestamp
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($events ?? [] as $event)
                <tr
                    wire:key="{{ $event->id }}"
                    class="border hover:bg-opacity-10 hover:bg-current border-neutral-200 dark:border-neutral-700">
                    <td class="px-6 py-4">
                        {{ $event->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $event->created_at->diffForHumans() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
