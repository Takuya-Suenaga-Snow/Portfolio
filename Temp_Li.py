# モジュールのインポート
from tqdm import tqdm
from PIL import Image
import numpy as np
import pathlib
import csv

'''------------------変数の設定------------------'''
path = '/Users/lab5-2019/Desktop/Python_test/test'  # パス
# ファイル名(拡張子なし)
file_name1 = '610'
file_name2 = '670'
nmax = 300  # ファイル数
tv = 15  # しきい値
cc = 0.349346975  # 補正係数
min_RI = 0.001319719  # 強度比下限
max_RI = 0.55557167   # 強度比上限
min_Temp = 3000   # 温度下限
max_Temp = 13000  # 温度上限
# 理論曲線の係数
C1 = -7661834.03381348  # xの6乘
C2 = 14100372.3590288   # xの5乘
C3 = -10107722.1241873  # xの4乘
C4 = 3573102.15513458   # xの3乘
C5 = -655105.590219645  # xの2乘
C6 = 74189.099235578    # x
C7 = 3183.2719737659    # 切片
'''--------------------------------------------'''

p = pathlib.Path(path)

#　BMPからCSVに変換
def BMPtoCSV(path):
    pathlib.Path(path+'/CSV').mkdir(exist_ok=True)  #　CSVディレクトリの作成
    pb = tqdm(range(nmax))  # プログレスバーの表示
    for i in range(nmax):
        pb.set_description('BMPtoCSV')
        num = str(i+1).zfill(6)
        # 画素値の取得
        image1 = np.array(Image.open(p.joinpath(path, 'FixData', file_name1+'_'+num+'.bmp')))
        image2 = np.array(Image.open(p.joinpath(path, 'FixData', file_name2+'_'+num+'.bmp')))
        #　CSVの保存
        np.savetxt(p.joinpath(path, 'CSV', file_name1+'_'+num+'.csv'), image1, delimiter=',', fmt='%d')
        np.savetxt(p.joinpath(path, 'CSV', file_name2+'_'+num+'.csv'), image2, delimiter=',', fmt='%d')
        pb.update(1)
    pb.close()

# 温度算出
def Temperature_Calculation(path):
    pb = tqdm(range(nmax))  # プログレスバーの表示
    for i in range(nmax):
        pb.set_description('Temperature_Calculation')
        num = str(i+1).zfill(6)
        # CSVの読み込み
        intensity1 = np.loadtxt(p.joinpath(path, 'CSV', file_name1+'_'+num+'.csv'), dtype=float, delimiter=',')
        intensity2 = np.loadtxt(p.joinpath(path, 'CSV', file_name2+'_'+num+'.csv'), dtype=float, delimiter=',')
        # しきい値以下を0
        intensity1[intensity1 < tv] = 0
        intensity2[intensity2 < tv] = 0
        # 強度比の算出    
        Relative_Intensity = np.divide(intensity1, intensity2, out=np.zeros_like(intensity1), where=intensity2!=0)
        RI = cc * Relative_Intensity
        # 温度算出
        Temp = np.zeros_like(intensity1)
        Temp[RI >= max_RI] = max_Temp
        Temp[RI <= min_RI] = min_Temp
        Temp = np.where((RI>min_RI)&(RI<max_RI), C1 * RI**6 + C2 * RI**5 + C3 * RI**4 + C4 * RI**3 + C5 * RI**2 + C6 * RI + C7, RI)
        # Tempの保存
        pathlib.Path(path+'/Temp').mkdir(exist_ok=True)  #Tempディレクトリの作成
        row, col = RI.shape
        with open(p.joinpath(path, 'Temp', 'Temp_'+num+'.csv'), 'w') as f:
            w = csv.writer(f)
            for j in range(row):
                for k in range(col):
                    w.writerow([j+1, k+1, Temp[j, k]])
        pb.update(1)
    pb.close()
                
def main():
    BMPtoCSV(path)
    Temperature_Calculation(path)

if __name__ == '__main__':
    main()