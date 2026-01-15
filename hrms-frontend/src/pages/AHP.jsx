import { useState, useEffect } from 'react';
import {
  Steps, Card, Button, Form, Input, Table, message, Space,
  Typography, Divider, Tag, InputNumber, Select
} from 'antd';
import {
  CheckCircleOutlined, WarningOutlined, LoadingOutlined
} from '@ant-design/icons';
import { ahpService } from '../services/ahpService';
import { useNavigate } from 'react-router-dom';

const { Title, Text } = Typography;
const { Step } = Steps;
const { TextArea } = Input;

const AHP = () => {
  const [currentStep, setCurrentStep] = useState(0);
  const [loading, setLoading] = useState(false);
  const [kriteria, setKriteria] = useState([]);
  const [sessionId, setSessionId] = useState(null);
  const [sessionData, setSessionData] = useState(null);
  const [comparisons, setComparisons] = useState([]);
  const [results, setResults] = useState(null);
  const [form] = Form.useForm();
  const navigate = useNavigate();

  useEffect(() => {
    fetchKriteria();
  }, []);

  const fetchKriteria = async () => {
    try {
      const response = await ahpService.getKriteria();
      if (response.success) {
        setKriteria(response.data);
        generateComparisons(response.data);
      }
    } catch (error) {
      message.error('Gagal mengambil data kriteria');
    }
  };

  const generateComparisons = (kriteriaList) => {
    const pairs = [];
    for (let i = 0; i < kriteriaList.length; i++) {
      for (let j = i + 1; j < kriteriaList.length; j++) {
        pairs.push({
          kriteria_1: kriteriaList[i],
          kriteria_2: kriteriaList[j],
          nilai: 1,
        });
      }
    }
    setComparisons(pairs);
  };

  const handleCreateSession = async (values) => {
    setLoading(true);
    try {
      const response = await ahpService.createSession(values);
      if (response.success) {
        setSessionId(response.data.id);
        setSessionData(response.data);
        message.success('Sesi AHP berhasil dibuat');
        setCurrentStep(1);
      }
    } catch (error) {
      message.error('Gagal membuat sesi AHP');
    } finally {
      setLoading(false);
    }
  };

  const handleSaveComparisons = async () => {
    setLoading(true);
    try {
      const comparisonData = comparisons.map(comp => ({
        kriteria_1_id: comp.kriteria_1.id,
        kriteria_2_id: comp.kriteria_2.id,
        nilai_perbandingan: parseFloat(comp.nilai),
      }));

      await ahpService.savePairwiseComparisons(sessionId, comparisonData);
      message.success('Perbandingan berpasangan berhasil disimpan');
      setCurrentStep(2);
    } catch (error) {
      message.error('Gagal menyimpan perbandingan');
    } finally {
      setLoading(false);
    }
  };

  const handleCalculate = async () => {
    setLoading(true);
    try {
      const response = await ahpService.calculate(sessionId);
      if (response.success) {
        setResults(response.data);
        message.success('Perhitungan AHP berhasil');
        setCurrentStep(3);
      }
    } catch (error) {
      message.error(error.response?.data?.message || 'Gagal melakukan perhitungan');
    } finally {
      setLoading(false);
    }
  };

  const handleComparisonChange = (index, value) => {
    const newComparisons = [...comparisons];
    newComparisons[index].nilai = value;
    setComparisons(newComparisons);
  };

  const ahpScale = [
    { value: 9, label: '9 - Mutlak lebih penting' },
    { value: 8, label: '8' },
    { value: 7, label: '7 - Sangat lebih penting' },
    { value: 6, label: '6' },
    { value: 5, label: '5 - Lebih penting' },
    { value: 4, label: '4' },
    { value: 3, label: '3 - Sedikit lebih penting' },
    { value: 2, label: '2' },
    { value: 1, label: '1 - Sama penting' },
    { value: 0.5, label: '1/2' },
    { value: 0.333, label: '1/3 - Sedikit kurang penting' },
    { value: 0.25, label: '1/4' },
    { value: 0.2, label: '1/5 - Kurang penting' },
    { value: 0.167, label: '1/6' },
    { value: 0.143, label: '1/7 - Sangat kurang penting' },
    { value: 0.125, label: '1/8' },
    { value: 0.111, label: '1/9 - Mutlak kurang penting' },
  ];

  const comparisonColumns = [
    {
      title: 'Kriteria A',
      dataIndex: 'kriteria_1',
      key: 'kriteria_1',
      render: (kriteria) => <Tag color="blue">{kriteria.nama}</Tag>,
    },
    {
      title: 'Nilai Perbandingan',
      key: 'nilai',
      width: 300,
      render: (_, record, index) => (
        <Select
          style={{ width: '100%' }}
          value={record.nilai}
          onChange={(value) => handleComparisonChange(index, value)}
        >
          {ahpScale.map(scale => (
            <Select.Option key={scale.value} value={scale.value}>
              {scale.label}
            </Select.Option>
          ))}
        </Select>
      ),
    },
    {
      title: 'Kriteria B',
      dataIndex: 'kriteria_2',
      key: 'kriteria_2',
      render: (kriteria) => <Tag color="green">{kriteria.nama}</Tag>,
    },
  ];

  const steps = [
    {
      title: 'Buat Sesi',
      content: (
        <Card>
          <Form
            form={form}
            layout="vertical"
            onFinish={handleCreateSession}
          >
            <Form.Item
              name="nama_sesi"
              label="Nama Sesi"
              rules={[{ required: true, message: 'Nama sesi harus diisi!' }]}
            >
              <Input placeholder="Contoh: Penilaian Karyawan Q1 2024" />
            </Form.Item>
            <Form.Item
              name="deskripsi"
              label="Deskripsi"
            >
              <TextArea rows={4} placeholder="Deskripsi sesi AHP..." />
            </Form.Item>
            <Form.Item
              name="periode"
              label="Periode"
            >
              <Input placeholder="Contoh: Q1 2024" />
            </Form.Item>
            <Form.Item>
              <Button type="primary" htmlType="submit" loading={loading}>
                Buat Sesi & Lanjutkan
              </Button>
            </Form.Item>
          </Form>
        </Card>
      ),
    },
    {
      title: 'Matriks Perbandingan',
      content: (
        <Card>
          <Title level={4}>Perbandingan Berpasangan Kriteria</Title>
          <Text type="secondary">
            Bandingkan setiap pasang kriteria menggunakan skala AHP (1-9)
          </Text>
          <Divider />
          <Table
            columns={comparisonColumns}
            dataSource={comparisons}
            rowKey={(record) => `${record.kriteria_1.id}-${record.kriteria_2.id}`}
            pagination={false}
          />
          <Divider />
          <Space>
            <Button onClick={() => setCurrentStep(0)}>
              Kembali
            </Button>
            <Button type="primary" onClick={handleSaveComparisons} loading={loading}>
              Simpan & Hitung
            </Button>
          </Space>
        </Card>
      ),
    },
    {
      title: 'Normalisasi & Bobot',
      content: (
        <Card>
          <Title level={4}>Proses Perhitungan</Title>
          <Divider />
          <Space direction="vertical" style={{ width: '100%' }}>
            <Button
              type="primary"
              size="large"
              icon={<LoadingOutlined />}
              onClick={handleCalculate}
              loading={loading}
            >
              Mulai Perhitungan AHP
            </Button>
            <Text type="secondary">
              Sistem akan menghitung matriks normalisasi, eigen vector, consistency index, dan consistency ratio.
            </Text>
          </Space>
        </Card>
      ),
    },
    {
      title: 'Hasil & Validasi',
      content: results && (
        <Card>
          <Title level={4}>Hasil Perhitungan AHP</Title>
          <Divider />

          {results.consistency_ratio !== undefined && (
            <div style={{ marginBottom: 24 }}>
              <Text strong>Consistency Ratio (CR): </Text>
              <Tag color={results.consistency_ratio < 0.1 ? 'success' : 'error'}>
                {(results.consistency_ratio * 100).toFixed(2)}%
              </Tag>
              {results.consistency_ratio < 0.1 ? (
                <Tag icon={<CheckCircleOutlined />} color="success">
                  Valid - CR {'<'} 10%
                </Tag>
              ) : (
                <Tag icon={<WarningOutlined />} color="error">
                  Tidak Valid - CR {'>'} 10%
                </Tag>
              )}
            </div>
          )}

          {results.weights && (
            <div>
              <Title level={5}>Bobot Prioritas Kriteria:</Title>
              <Table
                dataSource={kriteria.map((k, index) => ({
                  kriteria: k.nama,
                  bobot: results.weights[index],
                  persentase: (results.weights[index] * 100).toFixed(2) + '%',
                }))}
                columns={[
                  { title: 'Kriteria', dataIndex: 'kriteria', key: 'kriteria' },
                  { title: 'Bobot', dataIndex: 'bobot', key: 'bobot', render: (val) => val.toFixed(4) },
                  { title: 'Persentase', dataIndex: 'persentase', key: 'persentase' },
                ]}
                pagination={false}
              />
            </div>
          )}

          <Divider />
          <Space>
            <Button onClick={() => navigate('/results')}>
              Lihat Ranking Karyawan
            </Button>
            <Button type="primary" onClick={() => window.location.reload()}>
              Buat Sesi Baru
            </Button>
          </Space>
        </Card>
      ),
    },
  ];

  return (
    <div>
      <Title level={2}>Analisis AHP</Title>
      <Card>
        <Steps current={currentStep} style={{ marginBottom: 24 }}>
          {steps.map(item => (
            <Step key={item.title} title={item.title} />
          ))}
        </Steps>
        <div>{steps[currentStep].content}</div>
      </Card>
    </div>
  );
};

export default AHP;
