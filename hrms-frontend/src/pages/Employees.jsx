import { useState, useEffect } from 'react';
import {
  Table, Button, Input, Space, Modal, Form, message, Popconfirm,
  Tag, Typography, Card
} from 'antd';
import {
  PlusOutlined, EditOutlined, DeleteOutlined, SearchOutlined,
  EyeOutlined
} from '@ant-design/icons';
import { employeeService } from '../services/employeeService';
import dayjs from 'dayjs';

const { Title } = Typography;

const Employees = () => {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(false);
  const [modalVisible, setModalVisible] = useState(false);
  const [detailModalVisible, setDetailModalVisible] = useState(false);
  const [editingRecord, setEditingRecord] = useState(null);
  const [selectedRecord, setSelectedRecord] = useState(null);
  const [searchText, setSearchText] = useState('');
  const [pagination, setPagination] = useState({
    current: 1,
    pageSize: 10,
    total: 0,
  });
  const [form] = Form.useForm();

  useEffect(() => {
    fetchData();
  }, [pagination.current, searchText]);

  const fetchData = async () => {
    setLoading(true);
    try {
      const response = await employeeService.getAll({
        page: pagination.current,
        per_page: pagination.pageSize,
        search: searchText,
      });
      if (response.success) {
        setData(response.data.data);
        setPagination({
          ...pagination,
          total: response.data.total,
        });
      }
    } catch (error) {
      message.error('Gagal mengambil data karyawan');
    } finally {
      setLoading(false);
    }
  };

  const handleTableChange = (newPagination) => {
    setPagination(newPagination);
  };

  const handleSearch = (value) => {
    setSearchText(value);
    setPagination({ ...pagination, current: 1 });
  };

  const showModal = (record = null) => {
    setEditingRecord(record);
    if (record) {
      form.setFieldsValue({
        ...record,
        tanggal_masuk: dayjs(record.tanggal_masuk),
      });
    } else {
      form.resetFields();
    }
    setModalVisible(true);
  };

  const showDetail = (record) => {
    setSelectedRecord(record);
    setDetailModalVisible(true);
  };

  const handleSubmit = async (values) => {
    try {
      const data = {
        ...values,
        tanggal_masuk: values.tanggal_masuk.format('YYYY-MM-DD'),
      };

      if (editingRecord) {
        await employeeService.update(editingRecord.id, data);
        message.success('Data karyawan berhasil diupdate');
      } else {
        await employeeService.create(data);
        message.success('Data karyawan berhasil ditambahkan');
      }
      setModalVisible(false);
      fetchData();
    } catch (error) {
      message.error(error.response?.data?.message || 'Terjadi kesalahan');
    }
  };

  const handleDelete = async (id) => {
    try {
      await employeeService.delete(id);
      message.success('Data karyawan berhasil dihapus');
      fetchData();
    } catch (error) {
      message.error('Gagal menghapus data karyawan');
    }
  };

  const columns = [
    {
      title: 'NIP',
      dataIndex: 'nip',
      key: 'nip',
      width: 120,
    },
    {
      title: 'Nama',
      dataIndex: 'nama',
      key: 'nama',
    },
    {
      title: 'Email',
      dataIndex: 'email',
      key: 'email',
    },
    {
      title: 'Jabatan',
      dataIndex: 'jabatan',
      key: 'jabatan',
    },
    {
      title: 'Departemen',
      dataIndex: 'departemen',
      key: 'departemen',
    },
    {
      title: 'Status',
      dataIndex: 'status',
      key: 'status',
      render: (status) => {
        let color = status === 'aktif' ? 'green' : status === 'cuti' ? 'orange' : 'red';
        return <Tag color={color}>{status.toUpperCase()}</Tag>;
      },
    },
    {
      title: 'Aksi',
      key: 'action',
      width: 180,
      render: (_, record) => (
        <Space>
          <Button
            type="link"
            icon={<EyeOutlined />}
            onClick={() => showDetail(record)}
          >
            Detail
          </Button>
          <Button
            type="link"
            icon={<EditOutlined />}
            onClick={() => showModal(record)}
          >
            Edit
          </Button>
          <Popconfirm
            title="Apakah Anda yakin ingin menghapus data ini?"
            onConfirm={() => handleDelete(record.id)}
            okText="Ya"
            cancelText="Tidak"
          >
            <Button type="link" danger icon={<DeleteOutlined />}>
              Hapus
            </Button>
          </Popconfirm>
        </Space>
      ),
    },
  ];

  return (
    <div>
      <Title level={2}>Data Karyawan</Title>
      <Card>
        <Space style={{ marginBottom: 16 }}>
          <Input.Search
            placeholder="Cari karyawan..."
            allowClear
            onSearch={handleSearch}
            style={{ width: 300 }}
            prefix={<SearchOutlined />}
          />
          <Button
            type="primary"
            icon={<PlusOutlined />}
            onClick={() => showModal()}
          >
            Tambah Karyawan
          </Button>
        </Space>

        <Table
          columns={columns}
          dataSource={data}
          rowKey="id"
          loading={loading}
          pagination={pagination}
          onChange={handleTableChange}
        />
      </Card>

      <Modal
        title={editingRecord ? 'Edit Karyawan' : 'Tambah Karyawan'}
        open={modalVisible}
        onCancel={() => setModalVisible(false)}
        footer={null}
        width={600}
      >
        <Form
          form={form}
          layout="vertical"
          onFinish={handleSubmit}
        >
          <Form.Item
            name="nip"
            label="NIP"
            rules={[{ required: true, message: 'NIP harus diisi!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="nama"
            label="Nama"
            rules={[{ required: true, message: 'Nama harus diisi!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="email"
            label="Email"
            rules={[
              { required: true, message: 'Email harus diisi!' },
              { type: 'email', message: 'Format email tidak valid!' }
            ]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="jabatan"
            label="Jabatan"
            rules={[{ required: true, message: 'Jabatan harus diisi!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="departemen"
            label="Departemen"
            rules={[{ required: true, message: 'Departemen harus diisi!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="tanggal_masuk"
            label="Tanggal Masuk"
            rules={[{ required: true, message: 'Tanggal masuk harus diisi!' }]}
          >
            <Input type="date" />
          </Form.Item>
          <Form.Item
            name="status"
            label="Status"
            rules={[{ required: true, message: 'Status harus diisi!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item>
            <Space>
              <Button type="primary" htmlType="submit">
                Simpan
              </Button>
              <Button onClick={() => setModalVisible(false)}>
                Batal
              </Button>
            </Space>
          </Form.Item>
        </Form>
      </Modal>

      <Modal
        title="Detail Karyawan"
        open={detailModalVisible}
        onCancel={() => setDetailModalVisible(false)}
        footer={[
          <Button key="close" onClick={() => setDetailModalVisible(false)}>
            Tutup
          </Button>
        ]}
      >
        {selectedRecord && (
          <div>
            <p><strong>NIP:</strong> {selectedRecord.nip}</p>
            <p><strong>Nama:</strong> {selectedRecord.nama}</p>
            <p><strong>Email:</strong> {selectedRecord.email}</p>
            <p><strong>Jabatan:</strong> {selectedRecord.jabatan}</p>
            <p><strong>Departemen:</strong> {selectedRecord.departemen}</p>
            <p><strong>Tanggal Masuk:</strong> {dayjs(selectedRecord.tanggal_masuk).format('DD MMMM YYYY')}</p>
            <p><strong>Status:</strong> <Tag color={selectedRecord.status === 'aktif' ? 'green' : 'red'}>{selectedRecord.status.toUpperCase()}</Tag></p>
          </div>
        )}
      </Modal>
    </div>
  );
};

export default Employees;
