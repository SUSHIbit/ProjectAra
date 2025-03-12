import { createClient } from '@/lib/supabase/server';
import { redirect } from 'next/navigation';
import Link from 'next/link';

export default async function WorkerDashboard() {
  const supabase = createClient();
  
  const { data: { session } } = await supabase.auth.getSession();
  
  if (!session) {
    redirect('/login');
  }
  
  const { data: profile } = await supabase
    .from('profiles')
    .select('*')
    .eq('id', session.user.id)
    .single();
    
  const { data: transactions, error: transactionsError } = await supabase
    .from('transactions')
    .select(`
      *,
      services (
        name
      )
    `)
    .eq('worker_id', session.user.id)
    .order('created_at', { ascending: false })
    .limit(10);
  
  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-2xl font-bold mb-6">Worker Dashboard</h1>
      
      <div className="mb-8">
        <h2 className="text-xl font-semibold mb-4">Quick Actions</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <Link href="/worker/services" className="p-6 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition">
            <h3 className="text-lg font-medium">Create Transaction</h3>
            <p>Select services and generate QR code</p>
          </Link>
          
          <Link href="/worker/qrcode" className="p-6 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition">
            <h3 className="text-lg font-medium">Show QR Code</h3>
            <p>Display your active QR codes</p>
          </Link>
        </div>
      </div>
      
      <div>
        <h2 className="text-xl font-semibold mb-4">Recent Transactions</h2>
        
        {transactions && transactions.length > 0 ? (
          <div className="overflow-x-auto">
            <table className="min-w-full bg-white border border-gray-200">
              <thead>
                <tr>
                  <th className="px-4 py-2 border">Service</th>
                  <th className="px-4 py-2 border">Price</th>
                  <th className="px-4 py-2 border">Customer</th>
                  <th className="px-4 py-2 border">Status</th>
                  <th className="px-4 py-2 border">Date</th>
                </tr>
              </thead>
              <tbody>
                {transactions.map((transaction) => (
                  <tr key={transaction.id}>
                    <td className="px-4 py-2 border">{transaction.services.name}</td>
                    <td className="px-4 py-2 border">${(transaction.price / 100).toFixed(2)}</td>
                    <td className="px-4 py-2 border">{transaction.customer_name || 'N/A'}</td>
                    <td className="px-4 py-2 border">
                      <span className={`inline-block px-2 py-1 rounded text-xs ${
                        transaction.status === 'completed' 
                          ? 'bg-green-100 text-green-800' 
                          : transaction.status === 'cancelled'
                          ? 'bg-red-100 text-red-800'
                          : 'bg-yellow-100 text-yellow-800'
                      }`}>
                        {transaction.status}
                      </span>
                    </td>
                    <td className="px-4 py-2 border">
                      {new Date(transaction.created_at).toLocaleDateString()}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <p>No recent transactions found.</p>
        )}
      </div>
    </div>
  );
}