@include('emails.partials.header', ['subject' => 'Order Update'])

      <p style="margin:0 0 16px; color:#0f172a; font-size:18px; font-weight:700;">Your order has been updated.</p>
      <p style="margin:0 0 8px; color:#475569; font-size:14px; line-height:1.7;">
        Order <strong style="color:#0f172a;">{{ $order->order_number }}</strong> is now
        <strong style="color:#0f172a;">{{ ucwords(str_replace('_', ' ', $order->status)) }}</strong>.
      </p>

      @if($order->tracking_number)
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:16px 20px; margin:8px 0;">
          <tr>
            <td style="color:#1e40af; font-size:13px; line-height:1.7;">
              <strong>Tracking number:</strong>
              <span style="font-family:monospace;">{{ $order->tracking_number }}</span>
            </td>
          </tr>
        </table>
      @endif

      @if($order->status === 'cancelled')
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:16px 20px; margin:8px 0;">
          <tr>
            <td style="color:#991b1b; font-size:13px; line-height:1.7;">
              Any reserved stock has been returned to the archive. If you were charged, a refund has been arranged.
            </td>
          </tr>
        </table>
      @endif

@include('emails.partials.footer')