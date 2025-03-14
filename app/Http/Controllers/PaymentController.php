<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use WandesCardoso\MercadoPago\DTO\BackUrls;
use WandesCardoso\MercadoPago\Facades\MercadoPago;
use WandesCardoso\MercadoPago\DTO\Item;
use WandesCardoso\MercadoPago\DTO\Payer;
use WandesCardoso\MercadoPago\DTO\Payment as MercadoPagoPayment;
use WandesCardoso\MercadoPago\DTO\Preference;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{

    public function show($paymentId)
    {

        try {
            $payment = MercadoPago::payment()->find($paymentId);

            return response()->json([
                // 'data' => $payment['body'],
                'status' => $payment['body']->status,
                'payment_id' => $payment['body']->id,
                'payment_type' => $payment['body']->payment_type_id,
                'date_created' => $payment['body']->date_created,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }




    private function createItem(Request $request): Item
    {

        return Item::make()
            ->setId($request->plan_id)
            ->setTitle($request->plan_title)
            ->setQuantity(1)
            ->setUnitPrice(1)
            // ->setUnitPrice($request->amount)
            ->setDescription("Plano de assinatura")
            // ->setPictureUrl('https://www.mercadopago.com/org-img/MP3/home/logomp3.gif')
            ->setPictureUrl(public_path('gestab.jpg'))
            ->setCategoryId($request->payment_period);
    }

    public function createPreference(Request $request)
    {
        try {

            $phone = $request->user()->phone;
            $areaCode = substr($phone, 0, 2);
            $number = substr($phone, 2);

            $payer = new Payer(
                $request->user()->email,
                $request->user()->name,
                'Usário do GestaB',
                [],
                [
                    'area_code' => $areaCode,
                    'number' => $number
                ],
            );

            $item = $this->createItem($request);

            $removeClient = $request->remove_ads_client ? 1 : 0;
            // external_reference composto por establishment_id user_id
            //plan_id, quantity_professionals e remove_ads_client exemplo: E_1_U_1_P_1_Q_1_R_1
            $external_reference = 'ESTAB_' . $request->establishment_id . '_USER_' . $request->user()->id . '_PLAN_' . $request->plan_id . '_QTD_' . $request->quantity_professionals . '_REMCLI_' . $removeClient . '_PER_' . $request->payment_period;
            // $additional_info = [
            //     'quantity_professionals' => $request->quantity_professionals,
            //     'remove_ads_client' => $request->remove_ads_client,
            //     'plan_id' => $request->plan_id,
            //     'user_id' => $request->user()->id,
            //     'establishment_id' => $request->establishment_id,
            // ];

            $preference = Preference::make()
                ->setPayer($payer)
                ->addItem($item)
                ->setBackUrls(new BackUrls(
                    'com.marlof2.gestab://Plans?',
                    'com.marlof2.gestab://Plans?',
                    'com.marlof2.gestab://Plans?',
                ))
                ->setExternalReference($external_reference);
            // ->setAdditionalInfo(json_encode($additional_info))
            // ->setStatementDescriptor('GESTAB');

            $response = MercadoPago::preference()->create($preference);

            // Salve o preference_id no seu banco de dados junto com external_reference
            // para poder rastrear depois
            $preferenceData = [
                'preference_id' => $response['body']->collector_id,
                'external_reference' => $external_reference,
                'init_point' => $response['body']->init_point,
            ];

            return response()->json($preferenceData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function webhookMercadoPago(Request $request)
    {
        try {
            // Busca os dados do pagamento
            $paymentId = $request->data['id'];
            $payment = MercadoPago::payment()->find($paymentId);
            $payment = $payment['body'];

            $dataExternalReference = $this->extractIdsFromReference($payment->external_reference);
            // Processa o status do pagamento
            switch ($payment->status) {
                case 'approved':
                    // Pagamento aprovado - Libera o plano
                    return $this->createPaymentMercadoPago($payment, $dataExternalReference);
                    break;

                case 'pending':
                    // Pagamento pendente
                    return $this->createPaymentMercadoPago($payment, $dataExternalReference);
                    break;

                case 'rejected':
                    // Pagamento rejeitado
                    return $this->createPaymentMercadoPago($payment, $dataExternalReference);
                    break;

                case 'cancelled':
                    // Pagamento cancelado
                    return $this->createPaymentMercadoPago($payment, $dataExternalReference);
                    break;
            }

            return response()->json(['message' => 'Webhook processado com sucesso']);
        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function extractIdsFromReference(string $externalReference): array
    {
        $parts = explode('_', $externalReference);

        // Encontra o índice do identificador do usuário
        $userIndex = array_search('USER', $parts);
        if ($userIndex === false || !isset($parts[$userIndex + 1])) {
            throw new \InvalidArgumentException('Referência externa inválida: ID do usuário não encontrado');
        }

        // Encontra o índice do plano
        $planIndex = array_search('PLAN', $parts);
        if ($planIndex === false || !isset($parts[$planIndex + 1])) {
            throw new \InvalidArgumentException('Referência externa inválida: ID do plano não encontrado');
        }

        // Encontra o estabelecimento
        $establishmentIndex = array_search('ESTAB', $parts);
        if ($establishmentIndex === false || !isset($parts[$establishmentIndex + 1])) {
            throw new \InvalidArgumentException('Referência externa inválida: ID do estabelecimento não encontrado');
        }

        $quantityProfessionalsIndex = array_search('QTD', $parts);
        if ($quantityProfessionalsIndex === false || !isset($parts[$quantityProfessionalsIndex + 1])) {
            throw new \InvalidArgumentException('Referência externa inválida: Quantidade de profissionais não encontrada');
        }

        $removeAdsClientIndex = array_search('REMCLI', $parts);
        if ($removeAdsClientIndex === false || !isset($parts[$removeAdsClientIndex + 1])) {
            throw new \InvalidArgumentException('Referência externa inválida: Remover anúncios não encontrada');
        }

        $paymentPeriodIndex = array_search('PER', $parts);
        if ($paymentPeriodIndex === false || !isset($parts[$paymentPeriodIndex + 1])) {
            throw new \InvalidArgumentException('Referência externa inválida: Período de pagamento não encontrado');
        }




        return [
            'user_id' => (int) $parts[$userIndex + 1],
            'plan_id' => (int) $parts[$planIndex + 1],
            'establishment_id' => (int) $parts[$establishmentIndex + 1],
            'quantity_professionals' => (int) $parts[$quantityProfessionalsIndex + 1],
            'remove_ads_client' => (int) $parts[$removeAdsClientIndex + 1],
            'payment_period' => $parts[$paymentPeriodIndex + 1],
        ];
    }

    private function createPaymentMercadoPago(object $payment, array $dataExternalReference)
    {
        try {
            $subscriptionDates = $this->calculateSubscriptionDates($dataExternalReference['payment_period']);

            return $this->saveOrUpdatePayment($payment, $dataExternalReference, $subscriptionDates);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function calculateSubscriptionDates(string $paymentPeriod): array
    {
        $subscriptionStart = now();

        $subscriptionEnd = $paymentPeriod === 'monthly'
            ? $subscriptionStart->copy()->addMonth()
            : $subscriptionStart->copy()->addYear()->addMonth();

        return [
            'start' => $subscriptionStart,
            'end' => $subscriptionEnd
        ];
    }

    private function saveOrUpdatePayment(object $payment, array $dataExternalReference, array $subscriptionDates)
    {
        $existingPayment = Payment::where('payment_id', $payment->id)
            ->where('establishment_id', $dataExternalReference['establishment_id'])
            ->first();

        if ($existingPayment) {
            return $this->updateExistingPayment($existingPayment, $dataExternalReference, $payment, $subscriptionDates);
        }

        return $this->createNewPayment($payment, $dataExternalReference, $subscriptionDates);
    }

    private function updateExistingPayment(Payment $existingPayment, array $dataExternalReference, object $payment, array $subscriptionDates)
    {
        $existingPayment->update([
            'status' => $payment->status,
            'amount' => $payment->transaction_amount,
            'payment_method' => $payment->payment_type_id,
            'subscription_start' => $payment->status === 'approved' ? $subscriptionDates['start'] : null,
            'subscription_end' => $payment->status === 'approved' ? $subscriptionDates['end'] : null,
            'quantity_professionals' => $dataExternalReference['quantity_professionals'],
            'remove_ads_client' => $dataExternalReference['remove_ads_client'],
            'period' => $dataExternalReference['payment_period'],
        ]);

        return response()->json(['message' => 'Status do pagamento atualizado com sucesso']);
    }

    private function createNewPayment(object $payment, array $dataExternalReference, array $subscriptionDates)
    {

        Payment::create([
            'user_id' => $dataExternalReference['user_id'],
            'establishment_id' => $dataExternalReference['establishment_id'],
            'payment_id' => $payment->id,
            'amount' => $payment->transaction_amount,
            'status' => $payment->status,
            'payment_method' => $payment->payment_method_id,
            'preference_id' => $payment->collector_id,
            'external_reference' => $payment->external_reference,
            'plan_id' => $dataExternalReference['plan_id'],
            'quantity_professionals' => $dataExternalReference['quantity_professionals'],
            'remove_ads_client' => $dataExternalReference['remove_ads_client'],
            'period' => $dataExternalReference['payment_period'],
            'subscription_start' => $payment->status === 'approved' ? $subscriptionDates['start'] : null,
            'subscription_end' => $payment->status === 'approved' ? $subscriptionDates['end'] : null,
        ]);

        return response()->json(['message' => 'Plano ativado com sucesso']);
    }



    public function hasActivePayment(int $establishmentId)
    {
        $isActive = Payment::where('establishment_id', $establishmentId)
            ->where('status', 'approved')
            ->latest()
            ->first()?->isActive() ?? false;

        return response()->json([
            'isActive' => $isActive
        ]);
    }
}
