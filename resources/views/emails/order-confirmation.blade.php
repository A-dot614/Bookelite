@include('emails.partials.header', ['subject' => 'Order Registration'])

      <p style="margin:0 0 16px; color:#0f172a; font-size:18px; font-weight:700;">Thank you for your order.</p>
      <p style="margin:0 0 8px; color:#475569; font-size:14px; line-height:1.7;">
        Your order <strong style="color:#0f172a;">{{ $order->order_number }}</strong> has been placed into our
        archival fulfillment queue.
      </p>

      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        @foreach($order->items as $item)
          <tr>
            <td style="padding:8px 0; border-bottom:1px solid #f1f5f9; color:#0f172a; font-size:14px;">
              <strong>{{ $item->title }}</strong><br>
              <span style="color:#94a3b8; font-size:12px;">{{ $item->author }} · Qty {{ $item->quantity }}</span>
            </td>
            <td align="right" style="padding:8px 0; border-bottom:1px solid #f1f5f9; color:#0f172a; font-size:14px;">
              {{ config('ecommerce.currency_symbol') }}{{ number_format($item->line_total, 2) }}
            </td>
          </tr>
        @endforeach
        <tr>
          <td style="padding-top:16px; color:#475569; font-size:13px;">Subtotal</td>
          <td align="right" style="padding-top:16px; color:#0f172a; font-size:13px;">
            {{ config('ecommerce.currency_symbol') }}{{ number_format($order->subtotal, 2) }}
          </td>
        </tr>
        @if($order->shipping_cost > 0)
          <tr>
            <td style="padding-top:6px; color:#475569; font-size:13px;">Shipping</td>
            <td align="right" style="padding-top:6px; color:#0f172a; font-size:13px;">
              {{ config('ecommerce.currency_symbol') }}{{ number_format($order->shipping_cost, 2) }}
            </td>
          </tr>
        @endif
        <tr>
          <td style="padding-top:6px; border-top:2px solid #141414; color:#0f172a; font-size:15px; font-weight:700;">Total</td>
          <td align="right" style="padding-top:6px; border-top:2px solid #141414; color:#0f172a; font-size:15px; font-weight:700;">
            {{ config('ecommerce.currency_symbol') }}{{ number_format($order->total, 2) }}
          </td>
        </tr>
      </table>

      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:16px 20px; margin:8px 0;">
        <tr>
          <td style="color:#92400e; font-size:13px; line-height:1.7;">
            <strong>Payment pending.</strong> Your order is <em>not yet confirmed</em>. Payment method:
            <strong>{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</strong>.
            Reference <span style="font-family:monospace;">{{ $order->payment_reference }}</span>. We will email you
            the moment your payment clears.
          </td>
        </tr>
      </table>

@include('emails.partials.footer')