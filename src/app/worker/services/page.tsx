'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { createClient } from '@/lib/supabase/client';
import QRCode from 'qrcode.react';

export default function WorkerServicesPage() {
  const router = useRouter();
  const supabase = createClient();
  
  const [services, setServices] = useState<any[]>([]);
  const [selectedService, setSelectedService] = useState<string | null>(null);
  const [price, setPrice] = useState<number | null>(null);
  const [customerName, setCustomerName] = useState<string>('');
  const [loading, setLoading] = useState(false);
  const [qrCode, setQrCode] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);
  
  useEffect(() => {
    async function fetchServices() {
      const { data, error } = await supabase
        .from('services')
        .select('*')
        .order('name');
        
      if (error) {
        console.error('Error fetching services:', error);
        return;
      }
      
      setServices(data || []);
    }
    
    fetchServices();
  }, [supabase]);
  
  useEffect(() => {
    if (selectedService) {
      const service = services.find(s => s.id === selectedService);
      if (service) {
        setPrice(service.default_price);
      }
    } else {
      setPrice(null);
    }
  }, [selectedService, services]);
  
  const handleServiceChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    setSelectedService(e.target.value || null);
  };
  
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!selectedService || !price) {
      setError('Please select a service and enter a price');
      return;
    }
    
    try {
      setLoading(true);
      setError(null);
      
      // Get current user
      const { data: { session } } = await supabase.auth.getSession();
      
      if (!session) {
        router.push('/login');
        return;
      }
      
      // Generate a unique QR code
      const qrCodeValue = `${session.user.id}_${selectedService}_${Date.now()}`;
      
      // Create the transaction
      const { data, error } = await supabase
        .from('transactions')
        .insert({
          worker_id: session.user.id,
          service_id: selectedService,
          customer_name: customerName || null,
          price,
          qr_code: qrCodeValue,
          status: 'pending'
        })
        .select()
        .single();
        
      if (error) throw error;
      
      // Set the QR code to display
      setQrCode(qrCodeValue);
      
    } catch (error: any) {
      setError(error.message || 'An error occurred while creating the transaction');
    } finally {
      setLoading(false);
    }
  };
  
  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-2xl font-bold mb-6">Create Transaction</h1>
      
      {!qrCode ? (
        <div className="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label htmlFor="service" className="block text-sm font-medium text-gray-700 mb-1">
                Select Service
              </label>
              <select
                id="service"
                value={selectedService || ''}
                onChange={handleServiceChange}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                required
              >
                <option value="">-- Select a service --</option>
                {services.map((service) => (
                  <option key={service.id} value={service.id}>
                    {service.name} - ${(service.default_price / 100).toFixed(2)}
                  </option>
                ))}
              </select>
            </div>
            
            <div>
              <label htmlFor="price" className="block text-sm font-medium text-gray-700 mb-1">
                Price (in cents)
              </label>
              <input
                type="number"
                id="price"
                value={price || ''}
                onChange={(e) => setPrice(parseInt(e.target.value) || null)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                required
              />
              {price && (
                <p className="text-sm text-gray-500 mt-1">
                  ${(price / 100).toFixed(2)}
                </p>
              )}
            </div>
            
            <div>
              <label htmlFor="customerName" className="block text-sm font-medium text-gray-700 mb-1">
                Customer Name (optional)
              </label>
              <input
                type="text"
                id="customerName"
                value={customerName}
                onChange={(e) => setCustomerName(e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
            
            {error && (
              <div className="text-red-600 text-sm">{error}</div>
            )}
            
            <button
              type="submit"
              disabled={loading}
              className="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              {loading ? 'Processing...' : 'Generate QR Code'}
            </button>
          </form>
        </div>
      ) : (
        <div className="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto text-center">
          <h2 className="text-xl font-semibold mb-4">Your QR Code</h2>
          
          <div className="mb-6">
            <QRCode value={qrCode} size={200} className="mx-auto" />
          </div>
          
          <p className="mb-4">
            Show this QR code to the admin for payment processing.
          </p>
          
          <div className="flex space-x-4">
            <button
              onClick={() => setQrCode(null)}
              className="flex-1 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Create New
            </button>
            
            <button
              onClick={() => router.push('/worker/dashboard')}
              className="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Back to Dashboard
            </button>
          </div>
        </div>
      )}
    </div>
  );
}