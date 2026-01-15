import { useState, useEffect } from 'react';
import {
  Card, Table, Tag, Typography, Select, Space, Button, message, Spin
} from 'antd';
import { TrophyOutlined, DownloadOutlined } from '@ant-design/icons';
import { ahpService } from '../services/ahpService';

const { Title, Text } = Typography;

const Results = () => {
  const [loading, setLoading] = useState(false);
  const [sessions, setSessions] = useState([]);
  const [selectedSession, setSelectedSession] = useState(null);
  const [results, setResults] = useState([]);

  useEffect(() => {
    fetchSessions();
  }, []);

  const fetchSessions = async () => {
    try {
      const response = await ahpService.getSessions({ status: 'completed' });
      if (response.success) {
        setSessions(response.data.data || []);
        if (response.data.data && response.data.data.length > 0) {
          setSelectedSession(response.data.data[0].id);
          fetchResults(response.data.data[0].id);
        }
      }
    } catch (error) {
      message.error('Gagal mengambil data sesi');
    }
  };

  const fetchResults = async (sessionId) => {
    setLoading(true);
    try {
      const response = await ahpService.getResults(sessionId);
      if (response.success) {
        setResults(response.data.results || []);
      }
    } catch (error) {
      message.error('Gagal mengambil hasil keputusan');
    } finally {
      setLoading(false);
    }
  };

  const handleSessionChange = (sessionId) => {
    setSelectedSession(sessionId);
    fetchResults(sessionId);
  };

  const handleExportPDF = () => {
    message.info('Fitur export PDF akan segera tersedia');
  };

  const getRekomendasiColor = (rekomendasi) => {
    const colors = {
      'Sangat Layak': 'green',
      'Layak': 'blue',
      'Cukup Layak': 'orange',
      'Tidak Layak': 'red',
    };
    return colors[rekomendasi] || 'default';
  };

  const columns = [
    {
      title: 'Ranking',
      dataIndex: 'ranking',
      key: 'ranking',
      width: 100,
      align: 'center',
      render: (ranking) => {
        if (ranking === 1) {
          return <TrophyOutlined style={{ fontSize: 24, color: '#ffd700' }} />;
        } else if (ranking === 2) {
          return <TrophyOutlined style={{ fontSize: 22, color: '#c0c0c0' }} />;
        } else if (ranking === 3) {
          return <TrophyOutlined style={{ fontSize: 20, color: '#cd7f32' }} />;
        }
        return ranking;
      },
    },
    {
      title: 'NIP',
      dataIndex: ['employee', 'nip'],
      key: 'nip',
      width: 120,
    },
    {
      title: 'Nama Karyawan',
      dataIndex: ['employee', 'nama'],
      key: 'nama',
    },
    {
      title: 'Jabatan',
      dataIndex: ['employee', 'jabatan'],
      key: 'jabatan',
    },
    {
      title: 'Departemen',
      dataIndex: ['employee', 'departemen'],
      key: 'departemen',
    },
    {
      title: 'Nilai Akhir',
      dataIndex: 'nilai_akhir',
      key: 'nilai_akhir',
      align: 'center',
      render: (nilai) => (
        <Text strong style={{ fontSize: 16 }}>
          {parseFloat(nilai).toFixed(4)}
        </Text>
      ),
    },
    {
      title: 'Rekomendasi',
      dataIndex: 'rekomendasi',
      key: 'rekomendasi',
      align: 'center',
      render: (rekomendasi) => (
        <Tag color={getRekomendasiColor(rekomendasi)} style={{ fontSize: 14 }}>
          {rekomendasi}
        </Tag>
      ),
    },
  ];

  return (
    <div>
      <Title level={2}>Hasil Keputusan</Title>

      <Card>
        <Space style={{ marginBottom: 16, width: '100%', justifyContent: 'space-between' }}>
          <Space>
            <Text strong>Pilih Sesi:</Text>
            <Select
              style={{ width: 300 }}
              value={selectedSession}
              onChange={handleSessionChange}
              placeholder="Pilih sesi AHP"
            >
              {sessions.map(session => (
                <Select.Option key={session.id} value={session.id}>
                  {session.nama_session || `Sesi ${session.id}`} - {session.periode_awal}
                </Select.Option>
              ))}
            </Select>
          </Space>
          <Button
            type="primary"
            icon={<DownloadOutlined />}
            onClick={handleExportPDF}
          >
            Export PDF
          </Button>
        </Space>

        {loading ? (
          <div style={{ textAlign: 'center', padding: '50px' }}>
            <Spin size="large" />
          </div>
        ) : (
          <>
            {results.length > 0 ? (
              <Table
                columns={columns}
                dataSource={results}
                rowKey="id"
                pagination={false}
                bordered
              />
            ) : (
              <div style={{ textAlign: 'center', padding: '50px' }}>
                <Text type="secondary">
                  Belum ada hasil keputusan untuk sesi ini
                </Text>
              </div>
            )}
          </>
        )}
      </Card>
    </div>
  );
};

export default Results;
