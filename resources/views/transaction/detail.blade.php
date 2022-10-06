<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaction &raquo; {{ $item->craft->name }} by {{ $item->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full rounded overflow-hidden shadow-lg px-6 py-6 bg-white">
                <div class="flex flex-wrap -mx-4 -mb-4 md:mb-0">
                    <div class="w-full md:w-1/6 px-4 mb-4 md:mb-0">
                        <img src="{{ $item->craft->picturePath }}" alt="" class="w-full rounded">
                    </div>
                    <div class="w-full md:w-5/6 px-4 mb-4 md:mb-0">
                        <table class="table-auto w-full text-left">
                            <thead class="text-sm">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-xl font-bold">
                                <tr>
                                    <td>
                                        {{ $item->craft->name }}
                                    </td>
                                    <td>
                                        {{ number_format($item->quantity) }}
                                    </td>
                                    <td>
                                        {{ number_format($item->total) }}
                                    </td>
                                    <td>
                                        {{ $item->status }}
                                    </td>
                                </tr>
                            </tbody>
                            <thead class="text-sm">
                                <tr>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>House Number</th>
                                    <th>Postal Code</th>
                                </tr>
                            </thead>
                            <tbody class="text-xl font-bold">
                                <tr>
                                    <td>
                                        {{ $item->user->name }}
                                    </td>
                                    <td>
                                        {{ $item->user->email }}
                                    </td>
                                    <td>
                                        {{ $item->user->houseNumber }}
                                    </td>
                                    <td>
                                        {{ $item->user->postalCode }}
                                    </td>
                                </tr>
                            </tbody>
                            <thead class="text-sm">
                                <tr>
                                    <th class="w-4/6">Address</th>
                                    <th>Phone Number</th>
                                </tr>
                            </thead>
                            <tbody class="text-xl font-bold">
                                <tr>
                                    <td>
                                        {{ $item->user->address }}
                                    </td>
                                    <td>
                                        {{ $item->user->phoneNumber }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>

            <div class="w-full rounded overflow-hidden shadow-lg px-6 py-6 bg-white mt-5">
                <div class="flex flex-wrap -mx-4 -mb-4 md:mb-0">
                    <div class="w-full md:w-1/6 px-4 mb-4 md:mb-0">
                        <img src="{{ $item->craft->picturePath }}" alt="" class="w-full rounded">
                    </div>
                    <div class="w-full md:w-5/6 px-4 mb-4 md:mb-0">
                        <div class="flex flex-wrap mb-3">
                            <div class="w-2/6">
                                <div class="text-sm">Product Name</div>
                                <div class="text-xl font-bold">{{ $item->craft->name }}</div>
                            </div>
                            <div class="w-2/6">
                                <div class="text-sm">Quantity</div>
                                <div class="text-xl font-bold">{{ number_format($item->quantity) }}</div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm">Total</div>
                                <div class="text-xl font-bold">{{ number_format($item->total) }}</div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm">Status</div>
                                <div class="text-xl font-bold">{{ $item->status }}</div>
                            </div>
                        </div>
                        <div class="flex flex-wrap mb-3">
                            <div class="w-2/6">
                                <div class="text-sm">User Name</div>
                                <div class="text-xl font-bold">{{ $item->user->name }}</div>
                            </div>
                            <div class="w-2/6">
                                <div class="text-sm">Email</div>
                                <div class="text-xl font-bold">{{ $item->user->email }}</div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm">House Number</div>
                                <div class="text-xl font-bold">{{ $item->user->houseNumber }}</div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm">Phone Number</div>
                                <div class="text-xl font-bold">{{ $item->user->phoneNumber }}</div>
                            </div>
                        </div>
                        <div class="flex flex-wrap mb-3">
                            <div class="w-4/6">
                                <div class="text-sm">Address</div>
                                <div class="text-xl font-bold">{{ $item->user->address }}</div>
                            </div>
                            
                            <div class="w-2/6">
                                <div class="text-sm">Postal Code</div>
                                <div class="text-xl font-bold">{{ $item->user->phoneNumber }}</div>
                            </div>
                        </div>
                        <div class="flex flex-wrap mb-3">
                            <div class="w-5/6">
                                <div class="text-sm">Payment Url</div>
                                <div class="text-lg font-bold">
                                    <a href="{{ $item->payment_url }}">{{ $item->payment_url }}</a>
                                </div>
                            </div>
                            <div class="w-1/6">
                                <div class="text-sm mb-1">Change Status</div>
                                <a href="{{ route('transactions.changeStatus', ['id' => $item->id, 'status' => 'ON_DELIVERY']) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 rounded block text-center w-full mb-1">
                                    On Delivery
                                </a>
                                <a href="{{ route('transactions.changeStatus', ['id' => $item->id, 'status' => 'DELIVERED']) }}"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold px-2 rounded block text-center w-full mb-1">
                                    Delivered
                                </a>
                                <a href="{{ route('transactions.changeStatus', ['id' => $item->id, 'status' => 'CANCELLED']) }}"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold px-2 rounded block text-center w-full mb-1">
                                    Cancelled
                                </a>
                            </div>
                        </div>

                        </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
