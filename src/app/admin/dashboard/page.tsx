import { createClient } from '@/lib/supabase/server';
import { redirect } from 'next/navigation';
import Link from 'next/link';

export default async function AdminDashboard() {
  const supabase = createClient();
  
  const { data: { session } } = await supabase.auth.getSession();
  
  if (!session) {
    redirect('/login');
  }
  
  // Get all workers
  const { data: workers } = await supabase
    .from('profiles')
    .select('*')
    .eq('role', 'worker');
    
  // Get recent transactions
  const { data: transactions } = await supabase
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
    .order('created_at', { ascending: false })
    .limit(10);
    
  // Get transactions count by status
  const { data: transactionStats, error: statsError } = await supabase
    .from('transactions')
    .select('status, count')
    .group('status');
  
  // Format the stats
  const stats = {
    pending: 0,
    completed: 0,
    cancelled: 0
  };
  
  if (transactionStats) {
    transactionStats.forEach((stat) => {
      stats[stat.status as keyof typeof stats] = stat.count;
    });
  }
  
  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-2xl font-bold mb-6">Admin Dashboard</h1>
      
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div className="bg-white p-6 rounded-lg shadow-md">
          <h2 className="text-xl font-semibold mb-2">Transactions</h2>
          <div className="grid grid-cols-3 gap-4 text-center">
            <div>
              <p className="text-3xl font-bold text-yellow-500">{stats.pending}</p>
              <p className="text-sm text-gray-500">Pending</p>
            </div>
            <div>
              <p className="text-3xl font-bold text-green-500">{stats.completed}</p>
              <p className="text-sm text-gray-500">Completed</p>
            </div>
            <div>
              <p className="text-3xl font-bold text-red-500">{stats.cancelled}</p>
              <p className="text-sm text-gray-500">Cancelled</p>
            </div>
          </div>
        </div>
        
        <div className="bg-white p-6 rounded-lg shadow-md">
          <h2 className="text-xl font-semibold mb-2">Workers</h2>
          <p className="text-3xl font-bold text-blue-500">{workers?.length || 0}</p>
          <p className="text-sm text-gray-500">Total Workers</p>
        </div>
        
        <div className="bg-white p-6 rounded-lg shadow-md">
          <h2 className="text-xl font-semibold mb-2">Quick Actions</h2>
          <div className="space-y-2">
            <Link href="/admin/qrcodes" className="block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-center">
              Scan QR Code
            </Link>
            <Link href="/admin/services" className="block px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-center">
              Manage Services
            </Link>
          </div>
        </div>
      </div>
      
      <div>
        <h2 className="text-xl font-semibold mb-4">Recent Transactions</h2>
        
        {transactions && transactions.length > 0 ? (
          <div className="overflow-x-auto">
            <table className="min-w-full bg-white border border-gray-200">
              <thead>
                <tr>
                  <th className="px-4 py-2 border">Worker</th>
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
                    <td className="px-4 py-2 border">{transaction.profiles.email}</td>
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