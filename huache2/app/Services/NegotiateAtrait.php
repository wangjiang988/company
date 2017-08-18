<?php

namespace App\Services;

trait NegotiateAtrait
{
    public function getMemberConfirm($order)
    {
        $title = trans('orders.title.membernegotiation');
        return view('cart.Member_negotiate_confirm', compact('order', 'title'));
    }

    public function getMemberaAgreed($order)
    {
        $title = trans('orders.title.membernegotiation');
        return view('cart.Member_negotiate_confirm', compact('order', 'title'));
    }

    public function getMemberbAgreed($order)
    {
        $title = trans('orders.title.membernegotiation');
        return view('cart.Member_negotiate_confirm', compact('order', 'title'));
    }

    public function getMemberResult($order)
    {
        $title = trans('orders.title.negotiationmember');
        $jiaxinbaos = $order->orderjiaxinbao->where('role', 1);
        return view('cart.Member_negotiate_end',
            compact('order', 'title', 'jiaxinbaos'));
    }

    public function getMemberCase($order)
    {
        $jiaxinbaos = $order->orderjiaxinbao->where('role', 1);
        return view('cart.Member_break_termination',
            compact('order', 'jiaxinbaos'));
    }

    public function getMemberReason($order)
    {
        $jiaxinbaos = $order->orderjiaxinbao->where('role', 1);
        return view('cart.Member_break_termination',
            compact('order', 'jiaxinbaos'));
    }

    // ----------------------------------------seller-----------------

    public function getSellConfirm($order)
    {
        $title = trans('orders.title.sellernegotiation');
        $baojia = $order->orderBaojia;
        return view('dealer.orders.order_negotiate_confirm',
            compact('order', 'title', 'baojia'));
    }

    public function getSellaAgreed($order)
    {
        $title = trans('orders.title.sellernegotiation');
        $baojia = $order->orderBaojia;
        return view('dealer.orders.order_negotiate_confirm',
            compact('order', 'title', 'baojia'));
    }

    public function getSellbAgreed($order)
    {
        $title = trans('orders.title.waitingmembernego');
        $baojia = $order->orderBaojia;
        return view('dealer.orders.order_negotiate_confirm',
            compact('order', 'title', 'baojia'));
    }

    public function getSellResult($order)
    {
        $title = trans('orders.title.negotiationseller');
        $baojia = $order->orderBaojia;
        $jiaxinbaos = $order->orderjiaxinbao->where('role', 2);
        return view('dealer.orders.order_negotiate_end',
            compact('order', 'title', 'baojia', 'jiaxinbaos'));
    }

    public function getSellCase($order)
    {
        $baojia = $baojia = $order->orderBaojia;
        $jiaxinbaos = $order->orderjiaxinbao->where('role', 2);
        $title = trans('orders.title.refereeseller');
        return view('dealer.orders.order_break_termination',
            compact('order', 'baojia', 'jiaxinbaos', 'title'));
    }

    public function getSellReason($order)
    {
        $baojia = $baojia = $order->orderBaojia;
        $jiaxinbaos = $order->orderjiaxinbao->where('role', 2);
        $title = trans('orders.title.refereeseller');
        return view('dealer.orders.order_break_termination',
            compact('order', 'baojia', 'jiaxinbaos', 'title'));
    }


}
