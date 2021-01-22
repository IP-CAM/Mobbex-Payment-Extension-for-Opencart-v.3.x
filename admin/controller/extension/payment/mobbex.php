<?php
class ControllerExtensionPaymentMobbex extends Controller
{
    private $error = array();

    public function index() {
        $this->load->language('extension/payment/mobbex');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('payment_mobbex', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/mobbex', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/mobbex', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

        if (isset($this->request->post['payment_mobbex_status'])) {
            $data['payment_mobbex_status'] = $this->request->post['payment_mobbex_status'];
        } else {
            $data['payment_mobbex_status'] = $this->config->get('payment_mobbex_status');
        }

        if (isset($this->request->post['payment_mobbex_sort_order'])) {
            $data['payment_mobbex_sort_order'] = $this->request->post['payment_mobbex_sort_order'];
        } else {
            $data['payment_mobbex_sort_order'] = $this->config->get('payment_mobbex_sort_order');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['payment_mobbex_order_status_id'])) {
            $data['payment_mobbex_order_status_id'] = $this->request->post['payment_mobbex_order_status_id'];
        } else {
            $data['payment_mobbex_order_status_id'] = $this->config->get('payment_mobbex_order_status_id');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/mobbex', $data));
    }

    public function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/mobbex')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
