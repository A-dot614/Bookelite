@include('emails.partials.header', ['subject' => 'Payment Confirmed'])

      <p style="margin:0 0 16px; color:#0f172a; font-size:18px; font-weight:700;">Payment received — thank you.</p>
      <p style="margin:0 0 8px; color:#475569; font-size:14px; line-height:1.7;">
        Your payment for order <strong style="color:#0f172a;">{{ $order->order_number }}</strong> has been confirmed
        and your order is being prepared for dispatch.
      </p>

      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:16px 20px; margin:8px 0;">
        <tr>
          <td style="color:#166534; font-size:13px; line-height:1.7;">
            <strong>Status:</strong> {{ ucwords(str_replace('_', ' ', $order->status)) }}<br>
            <strong>Total:</strong> {{ config('ecommerce.currency_symbol') }}{{ number_format($order->total, 2) }}
          </td>
        </tr>
      </table>

@include('emails.partials.footer')