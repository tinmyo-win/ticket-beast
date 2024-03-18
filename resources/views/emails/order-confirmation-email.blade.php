<div>
    <p>Thank you for your order</p>
    <p>
        You can view your order at anytime by visiting this url
    </p>
    <p>
        <a href='{{ url("/orders/{$order->confirmation_number}") }}'>
            {{ url("/orders/{$order->confirmation_number}") }}
        </a>
    </p>
</div>