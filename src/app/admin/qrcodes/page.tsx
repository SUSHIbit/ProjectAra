'use client';

import { useState, useEffect } from 'react';
import { createClient } from '@/lib/supabase/client';
import { useRouter } from 'next/navigation';

export default function AdminQRCodePage() {
  const router = useRouter();
  const supabase = createClient();
  
  const [qrCode, setQrCode] = useState<string>('');
  const [transaction, setTransaction] = useState<any | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState<string | null>(null);
  
  const handleQrSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!qrCode.trim()) {
      setError('Please enter a QR code');
      return;
    }
    
    try {
      setLoading(true);
      setError(null);
      setSuccess(null);
      setTransaction(null);
      
      const { data, error } = await supabase
        .from('transactions')
        .select(`
          *,
          profiles (
            email
          ),
          services (
            name
          )
        `)
        .eq('qr_code', qrCode.trim())
        .eq('status', 'pending')
        .single();
        
      if (error) throw error;
      
      if (!data) {
        setError('Invalid QR code or transaction already processed');
        return;
      }
      
      setTransaction(data);
      
    } catch (error: any) {
      setError(error.message || 'An error occurred while fetching the transaction');
    } finally {
      setLoading(false);
    }
  };
  
  const handleStatusUpdate = async (status: 'completed' | 'cancelled') => {
    if (!transaction) return;
    
    try {
      setLoading(true);
      setError(null);
      setSuccess(null);
      
      const { error } = await supabase
        .from('transactions')
        .update({ status })
        .eq('id', transaction.id);
        
      if (error) throw error;
      
      setSuccess(`Transaction ${status} successfully!`);
      setTransaction(null);
      setQrCode('');
      
    } catch (error: any) {
      setError(error.message || `An error occurred while updating the transaction`);
    } finally {
      setLoading(false);
    }
  };
  
  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-2xl font-bold mb-6">Process QR Code</h1>
      
      <div className="max-w-md mx-auto">
        {error && (
          <div className="mb-4 p-4 bg-red-100 text-red-700 rounded">
            {error}
          </div>
        )}
        
        {success && (
          <div className="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {success}
          </div>
        )}
        
        <div className="bg-white p-6 rounded-lg shadow-md mb-6">
          <form onSubmit={handleQrSubmit}>
            <div className="mb-4">
              <label htmlFor="qrCode" className="block text-sm font-medium text-gray-700 mb-1">
                Enter QR Code
              </label>
              <input
                type="text"
                id="qrCode"
                value={qrCode}
                onChange={(e) => setQrCode(e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Enter the QR code value"
                required
              />
            </div>
            
            <button
              type="submit"
              disabled={loading}
              className="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              {loading ? 'Processing...' : 'Process QR Code'}
            </button>
          </form>
        </div>
        
        {transaction && (
          <div className="bg-white p-6 rounded-lg shadow-md">
            <h2 className="text-xl font-semibold mb-4">Transaction Details</h2>
            
            <div className="space-y-3 mb-6">
              <div className="grid grid-cols-2">
                <p className="text-gray-500">Worker:</p>
                <p className="font-medium">{transaction.profiles.email}</p>
              </div>
              
              <div className="grid grid-cols-2">
                <p className="text-gray-500">Service:</p>
                <p className="font-medium">{transaction.services.name}</p>
              </div>
              
              <div className="grid grid-cols-2">
                <p className="text-gray-500">Price:</p>
                <p className="font-medium">${(transaction.price / 100).toFixed(2)}</p>
              </div>
              
              {transaction.customer_name && (
                <div className="grid grid-cols-2">
                  <p className="text-gray-500">Customer:</p>
                  <p className="font-medium">{transaction.customer_name}</p>
                </div>
              )}
              
              <div className="grid grid-cols-2">
                <p className="text-gray-500">Created:</p>
                <p className="font-medium">{new Date(transaction.created_at).toLocaleString()}</p>
              </div>
            </div>
            
            <div className="flex space-x-4">
              <button
                onClick={() => handleStatusUpdate('completed')}
                disabled={loading}
                className="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
              >
                Complete Transaction
              </button>
              
              <button
                onClick={() => handleStatusUpdate('cancelled')}
                disabled={loading}
                className="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
              >
                Cancel Transaction
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}