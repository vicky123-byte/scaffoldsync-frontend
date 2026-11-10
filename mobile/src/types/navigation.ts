export type AppRole = 'pelanggan' | 'mandor';
export type ScreenName =
  | 'login' | 'register' | 'home' | 'catalog' | 'productDetail' | 'cart' | 'projectForm'
  | 'rentals' | 'rentalDetail' | 'uploadPayment' | 'profile' | 'help'
  | 'mandorDashboard' | 'taskDetail' | 'inspection' | 'photoEvidence'
  | 'signature' | 'bast' | 'favorites' | 'notifications';
export type SheetName = 'filter' | 'photoSource' | 'issue' | 'finish' | 'errors' | null;

export interface NavigationProps {
  navigate: (screen: ScreenName) => void;
}
