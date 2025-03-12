'use client';

import { useState, useEffect } from 'react';
import { createClient } from '@/lib/supabase/client';
import QRCode from 'qrcode.react';

export default function WorkerQRCodePage() {
  const supabase = createClient();
  
  const [transactions, setTransactions] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  
  useEffect(() => {
    async function fetchTransactions() {
      try {
        // Get current user
        const { data: { session } } = await supabase.auth.getSession();
        
        if (!session) {
          setError('No active session found. Please log in again.');
          setLoading(false);
          return;
        }
        
        // Get pending transactions
        const { data, error } = await supabase
          .from('transactions')
          .select(`
            *,
            services (
              name
            )
          `)
          .eq('worker_id', session.user.id)
          .eq('status', 'pending')
          .order('created_at', { ascending: false });
        
        if (error) throw error;
        
        setTransactions(data || []);
      } catch (error: any) {
        setError(error.message || 'An error occurred while fetching transactions');
      } finally {
        setLoading(false);
      }
    }
    
    fetchTransactions();
  }, [supabase]);
  
  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-2xl font-bold mb-6">My QR Codes</h1>
      
      {loading ? (
        <p>Loading...</p>
      ) : error ? (
        <div className="text-red-600">{error}</div>
      ) : transactions.length === 0 ? (
        <p>No pending transactions found. Create a new transaction first.</p>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {transactions.map((transaction) => (
            <div key={transaction.id} className="bg-white p-6 rounded-lg shadow-md">
              <h2 className="text-xl font-semibold mb-2">{transaction.services.name}</h2>
              <p className="mb-4">Price: ${(transaction.price / 100).toFixed(2)}</p>
              <div className="mb-4">
                <QRCode value={transaction.qr_code} size={180} className="mx-auto" />
              </div>
              <p className="text-sm text-gray-500">
                Created: {new Date(transaction.created_at).toLocaleString()}
              </p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}