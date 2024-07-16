<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Payment
    </h2>
  </x-slot>
  <div class="container mx-auto mt-8 p-4">
    <div class="flex">
      <!-- Transactions Table -->
      <div class="w-full">
        <div class="flex gap-5">
          <h2 class="text-2xl font-bold mb-4">Transactions</h2>
          <a href="{{ route('payment.month') }}" 
            class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 shadow-lg shadow-white-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-800 dark:bg-white dark:border-gray-700 dark:text-gray-900 dark:hover:bg-gray-200 me-2 mb-2">
            <svg aria-hidden="true" class="w-10 h-3 me-2 -ms-1" viewBox="0 0 660 203" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M233.003 199.762L266.362 4.002H319.72L286.336 199.762H233.003V199.762ZM479.113 8.222C468.544 4.256 451.978 0 431.292 0C378.566 0 341.429 26.551 341.111 64.604C340.814 92.733 367.626 108.426 387.865 117.789C408.636 127.387 415.617 133.505 415.517 142.072C415.384 155.195 398.931 161.187 383.593 161.187C362.238 161.187 350.892 158.22 333.368 150.914L326.49 147.803L319.003 191.625C331.466 197.092 354.511 201.824 378.441 202.07C434.531 202.07 470.943 175.822 471.357 135.185C471.556 112.915 457.341 95.97 426.556 81.997C407.906 72.941 396.484 66.898 396.605 57.728C396.605 49.591 406.273 40.89 427.165 40.89C444.611 40.619 457.253 44.424 467.101 48.39L471.882 50.649L479.113 8.222V8.222ZM616.423 3.99899H575.193C562.421 3.99899 552.861 7.485 547.253 20.233L468.008 199.633H524.039C524.039 199.633 533.198 175.512 535.27 170.215C541.393 170.215 595.825 170.299 603.606 170.299C605.202 177.153 610.098 199.633 610.098 199.633H659.61L616.423 3.993V3.99899ZM551.006 130.409C555.42 119.13 572.266 75.685 572.266 75.685C571.952 76.206 576.647 64.351 579.34 57.001L582.946 73.879C582.946 73.879 593.163 120.608 595.299 130.406H551.006V130.409V130.409ZM187.706 3.99899L135.467 137.499L129.902 110.37C120.176 79.096 89.8774 45.213 56.0044 28.25L103.771 199.45L160.226 199.387L244.23 3.99699L187.706 3.996"
                fill="#0E4595" />
              <path
                d="M86.723 3.99219H0.682003L0 8.06519C66.939 24.2692 111.23 63.4282 129.62 110.485L110.911 20.5252C107.682 8.12918 98.314 4.42918 86.725 3.99718"
                fill="#F2AE14" />
            </svg>
            Pay with Visa
          </a>
        </div>
        <table class="min-w-full bg-white rounded-lg shadow">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="py-2 px-4">#</th>
              <th class="py-2 px-4">Payer</th>
              <th class="py-2 px-4">Amount</th>
              <th class="py-2 px-4">Method</th>
              <th class="py-2 px-4">Transaction ID</th>
              <th class="py-2 px-4">Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach($payments as $index => $payment)
            <tr class="border-t">
              <td class="py-2 px-4"><span
                  class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{$index+1}}</span>
              </td>
              <td class="py-2 px-4">{{$payment->customer->name}}</td>
              <td class="py-2 px-4">${{$payment->amount}}</td>
              <td class="py-2 px-4">{{$payment->method}}</td>
              <td class="py-2 px-4">{{$payment->code}}</td>
              <td class="py-2 px-4">{{$payment->payment_date}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Calculate total amount from payments
      const totalAmountElement = document.getElementById('totalAmount').querySelector('strong');
      const amountCells = document.querySelectorAll('#booking-rows td:nth-child(4)'); // Selecting all amount cells in the table

      let totalAmount = 0;
      amountCells.forEach(cell => {
        totalAmount += parseFloat(cell.textContent); // Summing up the amounts
      });

      // Update the total amount displayed
      totalAmountElement.textContent = `$${totalAmount.toFixed(2)}`;
    });
  </script>
</x-app-layout>